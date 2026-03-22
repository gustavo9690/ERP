<?php

abstract class Repository
{
    /* =====================================================
       PROPIEDADES BASE
    ===================================================== */

    protected string $entityClass;
    protected PDO $db;

    /* =====================================================
       CONSTRUCTOR
    ===================================================== */

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    /* =====================================================
       METADATOS DE LA ENTIDAD
    ===================================================== */

    protected function getTable(): string
    {
        return $this->entityClass::getTable();
    }

    protected function getFields(): array
    {
        return $this->entityClass::getFields();
    }

    protected function getPK(): string
    {
        return $this->entityClass::getPrimaryKey();
    }

    protected function newQuery(): QueryBuilder
    {
        return new QueryBuilder(
            $this->getTable(),
            $this->getFields()
        );
    }

    /* =====================================================
       CONFIGURACIÓN DE SOFT DELETE
    ===================================================== */

    protected function usesSoftDelete(): bool
    {
        return method_exists($this->entityClass, 'usesSoftDelete')
            ? $this->entityClass::usesSoftDelete()
            : false;
    }

    protected function getSoftDeleteField(): string
    {
        return method_exists($this->entityClass, 'getSoftDeleteField')
            ? $this->entityClass::getSoftDeleteField()
            : 'estado';
    }

    protected function buildSoftDeleteClause(string $prefix = ''): array
    {
        if (!$this->usesSoftDelete()) {
            return ['', []];
        }

        $field = $this->getSoftDeleteField();
        $column = $prefix ? "{$prefix}.{$field}" : $field;

        return ["{$column} = :__softDelete", ['__softDelete' => 1]];
    }

    /* =====================================================
       MÉTODOS AUXILIARES DE EJECUCIÓN
    ===================================================== */

    protected function execute(string $sql, array $params = []): bool
    {
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    protected function query(string $sql, array $params = []): PDOStatement
    {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    /* =====================================================
       CONSULTAS BÁSICAS
    ===================================================== */

    public function findAll(): array
    {
        [$softDeleteSql, $softDeleteParams] = $this->buildSoftDeleteClause($this->getTable());

        $qb = $this->newQuery()
            ->selectEntityFields();

        if ($softDeleteSql) {
            $qb->where($softDeleteSql, $softDeleteParams);
        }

        $stmt = $this->query($qb->toSql(), $qb->getParams());

        return $this->mapResults($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public function findById($id)
    {
        $pk = $this->getPK();

        [$softDeleteSql, $softDeleteParams] = $this->buildSoftDeleteClause($this->getTable());

        $qb = $this->newQuery()
            ->selectEntityFields()
            ->where("{$this->getTable()}.{$pk} = :id", ['id' => $id])
            ->limit(1);

        if ($softDeleteSql) {
            $qb->where($softDeleteSql, $softDeleteParams);
        }

        $stmt = $this->query($qb->toSql(), $qb->getParams());

        return $this->mapResult($stmt->fetch(PDO::FETCH_ASSOC));
    }

    /* =====================================================
       INSERT
    ===================================================== */

    public function insert(Entity $entity): bool
    {
        $table = $this->getTable();
        $pk = $this->getPK();
        $fields = $this->getFields();
        $data = $entity->toArray();

        $columns = [];
        $placeholders = [];
        $params = [];

        foreach ($fields as $column => $config) {
            $alias = $config['alias'] ?? $column;

            if ($column === $pk && (!isset($data[$alias]) || $data[$alias] === null || $data[$alias] === '')) {
                continue;
            }

            if (!array_key_exists($alias, $data)) {
                continue;
            }

            $value = $data[$alias];

            if (!empty($config['fk']) && is_array($value)) {
                $value = $this->extractForeignKeyValue($config, $value);
            }

            $columns[] = $column;
            $placeholders[] = ':' . $column;
            $params[$column] = $value;
        }

        if (empty($columns)) {
            throw new Exception("No hay datos para insertar en {$table}");
        }

        $sql = "INSERT INTO {$table} (" . implode(', ', $columns) . ")
                VALUES (" . implode(', ', $placeholders) . ")";

        return $this->execute($sql, $params);
    }

    /* =====================================================
       UPDATE
    ===================================================== */

    public function update(Entity $entity): bool
    {
        $table = $this->getTable();
        $pk = $this->getPK();
        $fields = $this->getFields();
        $data = $entity->toArray();

        $pkAlias = $this->entityClass::getAliasByColumn($pk);
        $pkValue = $data[$pkAlias] ?? null;

        if ($pkValue === null || $pkValue === '') {
            throw new Exception("No se puede actualizar: falta la llave primaria");
        }

        $setParts = [];
        $params = [];

        foreach ($fields as $column => $config) {
            $alias = $config['alias'] ?? $column;

            if ($column === $pk) {
                continue;
            }

            if (!array_key_exists($alias, $data)) {
                continue;
            }

            $value = $data[$alias];

            if (!empty($config['fk']) && is_array($value)) {
                $value = $this->extractForeignKeyValue($config, $value);
            }

            $setParts[] = "{$column} = :{$column}";
            $params[$column] = $value;
        }

        if (empty($setParts)) {
            throw new Exception("No hay campos para actualizar en {$table}");
        }

        $params['__pk'] = $pkValue;

        $sql = "UPDATE {$table}
                SET " . implode(', ', $setParts) . "
                WHERE {$pk} = :__pk";

        return $this->execute($sql, $params);
    }

    /* =====================================================
       SAVE
    ===================================================== */

    public function save(Entity $entity): bool
    {
        $pk = $this->getPK();
        $pkAlias = $this->entityClass::getAliasByColumn($pk);
        $data = $entity->toArray();

        $pkValue = $data[$pkAlias] ?? null;

        if ($pkValue === null || $pkValue === '') {
            return $this->insert($entity);
        }

        return $this->update($entity);
    }

    /* =====================================================
       DELETE FÍSICO
    ===================================================== */

    public function delete($id): bool
    {
        $table = $this->getTable();
        $pk = $this->getPK();

        $sql = "DELETE FROM {$table} WHERE {$pk} = :id";

        return $this->execute($sql, ['id' => $id]);
    }

    /* =====================================================
       DELETE LÓGICO
    ===================================================== */

    public function softDelete($id): bool
    {
        if (!$this->usesSoftDelete()) {
            throw new Exception("La entidad no usa borrado lógico");
        }

        $table = $this->getTable();
        $pk = $this->getPK();
        $field = $this->getSoftDeleteField();

        $sql = "UPDATE {$table} SET {$field} = :estado WHERE {$pk} = :id";

        return $this->execute($sql, [
            'estado' => 0,
            'id' => $id
        ]);
    }

    /* =====================================================
       MÉTODOS DINÁMICOS
    ===================================================== */

    public function __call($method, $args)
    {
        if (strpos($method, 'findBy') === 0) {
            return $this->handleDynamicFind($method, $args);
        }

        throw new Exception("Método {$method} no existe");
    }

    private function handleDynamicFind(string $method, array $args)
    {
        $conditionsPart = substr($method, 6);

        preg_match_all(
            '/(And|Or)?([A-Z][a-zA-Z0-9]*?)(Like)?(?=And|Or|$)/',
            $conditionsPart,
            $matches,
            PREG_SET_ORDER
        );

        if (empty($matches)) {
            throw new Exception("Método dinámico inválido: {$method}");
        }

        $parts = [];
        $params = [];
        $argIndex = 0;

        foreach ($matches as $index => $match) {
            $logical = strtoupper($match[1] ?? '');
            $fieldName = $match[2] ?? '';
            $isLike = !empty($match[3]);

            $alias = lcfirst($fieldName);
            $column = $this->entityClass::getColumnByAlias($alias);

            if (!$column) {
                throw new Exception("Campo {$fieldName} no existe en la Entity");
            }

            if (!array_key_exists($argIndex, $args)) {
                throw new Exception("Falta valor para el campo {$fieldName}");
            }

            $paramKey = "param{$argIndex}";
            $condition = $isLike
                ? "{$this->getTable()}.{$column} LIKE :{$paramKey}"
                : "{$this->getTable()}.{$column} = :{$paramKey}";

            if ($index > 0) {
                $parts[] = $logical ?: 'AND';
            }

            $parts[] = $condition;
            $params[$paramKey] = $isLike
                ? '%' . $args[$argIndex] . '%'
                : $args[$argIndex];

            $argIndex++;
        }

        $where = implode(' ', $parts);

        [$softDeleteSql, $softDeleteParams] = $this->buildSoftDeleteClause($this->getTable());

        $qb = $this->newQuery()
            ->selectEntityFields()
            ->where("({$where})", $params);

        if ($softDeleteSql) {
            $qb->where($softDeleteSql, $softDeleteParams);
        }

        $stmt = $this->query($qb->toSql(), $qb->getParams());

        $results = $this->mapResults($stmt->fetchAll(PDO::FETCH_ASSOC));

        return count($results) === 1 ? $results[0] : $results;
    }

    /* =====================================================
       MAPEO DE RESULTADOS
    ===================================================== */

    protected function mapResults(array $rows): array
    {
        return array_map(fn($row) => $this->mapResult($row), $rows);
    }

    protected function mapResult(?array $row)
    {
        if (!$row) {
            return null;
        }

        $mapped = [];
        $fields = $this->getFields();

        foreach ($fields as $column => $config) {
            $alias = $config['alias'] ?? $column;
            $value = $row[$column] ?? null;

            if (!empty($config['fk'])) {
                if (!empty($config['embed'])) {
                    $relation = $this->loadRelation($config, $value);

                    if ($relation instanceof Entity) {
                        $mapped[$alias] = $relation->toArray();
                    } else {
                        $mapped[$alias] = $relation;
                    }
                } else {
                    $mapped[$alias] = $value;
                }
            } else {
                $mapped[$alias] = $value;
            }
        }

        return new $this->entityClass($mapped);
    }

    /* =====================================================
       RELACIONES
    ===================================================== */

    private function loadRelation(array $config, $value)
    {
        if (!$value || empty($config['ref'])) {
            return null;
        }

        $table = $config['ref']['table'] ?? null;
        $column = $config['ref']['column'] ?? null;

        if (!$table || !$column) {
            return null;
        }

        $sql = "SELECT * FROM {$table} WHERE {$column} = :id LIMIT 1";

        $stmt = $this->query($sql, ['id' => $value]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$data) {
            return null;
        }

        if (!empty($config['ref']['entity'])) {
            $entityClass = $config['ref']['entity'];
            return new $entityClass($data);
        }

        return $data;
    }

    private function extractForeignKeyValue(array $config, array $value)
    {
        if (empty($config['ref']['entity'])) {
            return $value['id'] ?? reset($value) ?? null;
        }

        $refEntityClass = $config['ref']['entity'];
        $refPk = $refEntityClass::getPrimaryKey();
        $refPkAlias = $refEntityClass::getAliasByColumn($refPk);

        return $value[$refPkAlias] ?? null;
    }
}