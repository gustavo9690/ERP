<?php

abstract class Entity extends Model
{
    protected static string $table;
    protected static string $primaryKey = 'id';

    protected static bool $softDelete = false;
    protected static string $softDeleteField = 'estado';

    protected static string $deleteMode = 'active';

     // alias => columna_bd
    protected static array $fields = [];

    protected array $attributes = [];

    public function __construct(array $data = [])
    {
        parent::__construct();
        foreach (static::$fields as $property => $column) {
            $this->attributes[$property] = null;
        }

        foreach ($data as $key => $value) {
            if (array_key_exists($key, static::$fields)) {
                $this->attributes[$key] = $value;
            }
        }
    }

    public function __get($name)
    {
        return $this->attributes[$name] ?? null;
    }

    public function __set($name, $value)
    {
        if (array_key_exists($name, static::$fields)) {
            $this->attributes[$name] = $value;
        }
    }

    protected function getFillableData(): array
    {
        $final = [];

        foreach (static::$fields as $property => $column) {

            $value = $this->attributes[$property] ?? null;

            // 🚀 1️⃣ No enviar NULL
            if ($value === null) {
                continue;
            }

            // 🚀 2️⃣ No enviar primary key si es null (autoincrement)
            if ($column === static::$primaryKey && $value === null) {
                continue;
            }

            $final[$column] = $value;
        }

        return $final;
    }

    protected static function map(array $data): static
    {
        $obj = new static();

        foreach ($data as $column => $value) {
            $property = array_search($column, static::$fields, true);
            if ($property !== false) {
                $obj->attributes[$property] = $value;
            }
        }

        return $obj;
    }

    public function toArray(): array
    {
        return $this->attributes;
    }


    protected static function mapAll(array $rows): array
    {
        return array_map(function ($row) {
            return static::map($row);
        }, $rows);
    }
    
    /* =====================================================
       FIND POR ID
    ===================================================== */
    public static function find($id): ?static
    {
        $instance = new static();
        $table = static::$table;
        $pk = static::$primaryKey;

        $data = $instance->fetch(
            "SELECT * FROM $table WHERE $pk = :id",
            ['id' => $id]
        );

        if (!$data) return null;

        return static::map($data);
    }

    /* =====================================================
       INSERT
    ===================================================== */
    public function insert(): bool
    {
        $table = static::$table;
        $data = $this->getFillableData();

        $columns = array_keys($data);
        $placeholders = array_map(fn($col) => ":$col", $columns);

        $sql = "INSERT INTO $table (" . implode(",", $columns) . ")
                VALUES (" . implode(",", $placeholders) . ")";

        return $this->execute($sql, $data);
    }

    /* =====================================================
       UPDATE
    ===================================================== */
    public function update(): bool
    {
        $table = static::$table;
        $pkColumn = static::$primaryKey;

        $pkProperty = array_search($pkColumn, static::$fields, true);

        if ($pkProperty === false) {
            throw new Exception("Primary key no definida.");
        }

        $id = $this->attributes[$pkProperty] ?? null;

        if ($id === null) {
            throw new Exception("No se puede hacer update sin ID.");
        }

        $data = $this->getFillableData();

        unset($data[$pkColumn]);

        $set = implode(", ", array_map(
            fn($col) => "$col = :$col",
            array_keys($data)
        ));

        $sql = "UPDATE $table SET $set WHERE $pkColumn = :pk";

        $data['pk'] = $id;

        return $this->execute($sql, $data);
    }

    /* =====================================================
       DELETE
    ===================================================== */
    public function delete(): bool
    {
        // 🔹 Si la entidad usa soft delete
        if (static::$softDelete) {
            return $this->softDelete();
        }

        return $this->forceDelete();
    }

    /* =====================================================
       MAGIC FIND BY (AND / OR / LIKE)
    ===================================================== */
    public static function __callStatic($method, $arguments)
    {
        $instance = new static();
        $table = static::$table;

        /* =========================
        FIND BY LIKE
        ========================== */
        if (str_starts_with($method, 'findBy') && str_contains($method, 'Like')) {

            $fieldPart = str_replace(['findBy', 'Like'], '', $method);
            $property = lcfirst($fieldPart);

            if (!isset(static::$fields[$property])) {
                throw new Exception("Campo $property no existe en la entidad.");
            }

            $column = static::$fields[$property];

            $sql = "SELECT * FROM $table WHERE $column LIKE :value";

            $rows = $instance->fetchAll($sql, [
                'value' => "%{$arguments[0]}%"
            ]);

            return static::mapAll($rows);
        }

        /* =========================
        FIND BY AND / OR
        ========================== */
        if (str_starts_with($method, 'findBy')) {

            $fieldsPart = str_replace('findBy', '', $method);

            $pattern = '/(And|Or)/';
            $parts = preg_split($pattern, $fieldsPart, -1, PREG_SPLIT_DELIM_CAPTURE);

            $conditions = [];
            $params = [];
            $argIndex = 0;

            foreach ($parts as $part) {

                if ($part === 'And' || $part === 'Or') {
                    $conditions[] = strtoupper($part);
                } else {

                    $property = lcfirst($part);

                    if (!isset(static::$fields[$property])) {
                        throw new Exception("Campo $property no existe en la entidad.");
                    }

                    $column = static::$fields[$property];

                    $conditions[] = "$column = :$property";
                    $params[$property] = $arguments[$argIndex++] ?? null;
                }
            }

            $where = implode(' ', $conditions);

            $sql = "SELECT * FROM $table WHERE $where";

            $rows = $instance->fetchAll($sql, $params);

            return static::mapAll($rows);
        }

        throw new BadMethodCallException("Método $method no existe.");
    }

    /* =====================================================
    SAVE (INSERT o UPDATE automático)
    ===================================================== */
    public function save(): bool
    {
        $pkColumn = static::$primaryKey;

        // Buscar qué propiedad corresponde a la PK
        $pkProperty = array_search($pkColumn, static::$fields, true);

        if ($pkProperty === false) {
            throw new Exception("Primary key no definida en \$fields.");
        }

        $id = $this->attributes[$pkProperty] ?? null;

        // 🔹 Si no tiene ID → INSERT
        if ($id === null) {

            $result = $this->insert();

            // 🔥 Asignar lastInsertId automáticamente
            if ($result) {
                $lastId = $this->db->lastInsertId();
                $this->attributes[$pkProperty] = $lastId;
            }

            return $result;
        }

        // 🔹 Si tiene ID → UPDATE
        return $this->update();
    }


    /* =====================================================
    SOFT DELETE
    ===================================================== */
    public function softDelete(): bool
    {
        $table = static::$table;
        $pkColumn = static::$primaryKey;
        $fieldColumn = static::$fields[static::$softDeleteField] ?? null;

        if (!$fieldColumn) {
            throw new Exception("Campo soft delete no definido correctamente.");
        }

        $pkProperty = array_search($pkColumn, static::$fields, true);

        if ($pkProperty === false) {
            throw new Exception("Primary key no definida.");
        }

        $id = $this->$pkProperty;

        $sql = "UPDATE $table 
                SET $fieldColumn = 0 
                WHERE $pkColumn = :id";

        return $this->execute($sql, ['id' => $id]);
    }

    /* =====================================================
    RESTORE
    ===================================================== */
    public function restore(): bool
    {
        $field = static::$softDeleteField;

        if (!isset(static::$fields[$field])) {
            throw new Exception("Campo soft delete no definido en \$fields.");
        }

        $this->$field = 1;
        return $this->save();
    }

    /* =====================================================
    HARD DELETE REAL
    ===================================================== */
    public function forceDelete(): bool
    {
        $table = static::$table;
        $pkColumn = static::$primaryKey;

        $pkProperty = array_search($pkColumn, static::$fields, true);

        if ($pkProperty === false) {
            throw new Exception("Primary key no definida.");
        }

        return $this->execute(
            "DELETE FROM $table WHERE $pkColumn = :id",
            ['id' => $this->$pkProperty]
        );
    }


    public static function withDeleted(): static
    {
        static::$deleteMode = 'with';
        return new static();
    }

    public static function onlyDeleted(): static
    {
        static::$deleteMode = 'only';
        return new static();
    }

    /* =====================================================
    ALL
    ===================================================== */
    public static function all(): array
    {
        $instance = new static();
        $table = static::$table;

        $sql = "SELECT * FROM $table";

        // 🔹 Manejo de soft delete
        if (static::$softDelete) {

            $fieldProperty = static::$softDeleteField;
            $column = static::$fields[$fieldProperty] ?? null;

            if ($column) {

                if (static::$deleteMode === 'active') {
                    $sql .= " WHERE $column = 1";
                }

                if (static::$deleteMode === 'only') {
                    $sql .= " WHERE $column = 0";
                }
            }
        }

        $rows = $instance->fetchAll($sql);

        // 🔥 Resetear modo delete después de usarlo
        static::$deleteMode = 'active';

        return static::mapAll($rows);
    }
}