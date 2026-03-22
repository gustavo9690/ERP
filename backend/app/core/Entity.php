<?php

abstract class Entity implements JsonSerializable
{
    protected static string $table;
    protected static string $primaryKey = 'id';
    protected static bool $softDelete = false;
    protected static string $softDeleteField = 'estado';
    protected static string $deleteMode = 'active';
    protected static array $fields = [];

    protected array $attributes = [];

    public function __construct(array $data = [])
    {
        foreach (static::$fields as $column => $config) {
            $alias = $config['alias'] ?? $column;
            $this->attributes[$alias] = null;
        }

        $this->fill($data);
    }

    public function fill(array $data): void
    {
        foreach ($data as $key => $value) {
            if (array_key_exists($key, $this->attributes)) {
                $this->attributes[$key] = $value;
                continue;
            }

            $alias = static::getAliasByColumn($key);

            if ($alias !== null && array_key_exists($alias, $this->attributes)) {
                $this->attributes[$alias] = $value;
            }
        }
    }

    public function toArray(): array
    {
        return $this->attributes;
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    public function __get($name)
    {
        return $this->attributes[$name] ?? null;
    }

    public function __set($name, $value)
    {
        if (array_key_exists($name, $this->attributes)) {
            $this->attributes[$name] = $value;
        }
    }

    public static function getTable(): string
    {
        return static::$table;
    }

    public static function getPrimaryKey(): string
    {
        return static::$primaryKey;
    }

    public static function getFields(): array
    {
        return static::$fields;
    }

    public static function getColumnByAlias(string $alias): ?string
    {
        foreach (static::$fields as $column => $config) {
            if (($config['alias'] ?? $column) === $alias) {
                return $column;
            }
        }

        return null;
    }

    public static function getAliasByColumn(string $column): ?string
    {
        if (!isset(static::$fields[$column])) {
            return null;
        }

        return static::$fields[$column]['alias'] ?? $column;
    }

    public static function usesSoftDelete(): bool
    {
        return static::$softDelete;
    }

    public static function getSoftDeleteField(): string
    {
        return static::$softDeleteField;
    }

    public static function getDeleteMode(): string
    {
        return static::$deleteMode;
    }
}