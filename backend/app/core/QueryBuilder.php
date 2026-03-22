<?php

class QueryBuilder
{
    private string $table;
    private array $fields;

    private array $selects = [];
    private array $joins = [];
    private array $wheres = [];
    private array $params = [];
    private ?int $limit = null;

    public function __construct(string $table, array $fields)
    {
        $this->table = $table;
        $this->fields = $fields;
    }

    public function select(array $columns = ['*']): self
    {
        $this->selects = $columns;
        return $this;
    }

    public function selectEntityFields(): self
    {
        $this->selects = [];

        foreach ($this->fields as $column => $config) {
            $alias = $config['alias'] ?? $column;
            $this->selects[] = "{$this->table}.{$column} AS {$column}";
        }

        return $this;
    }

    public function withRelations(): self
    {
        foreach ($this->fields as $column => $config) {
            if (empty($config['fk']) || empty($config['ref'])) {
                continue;
            }

            $refTable = $config['ref']['table'] ?? null;
            $refColumn = $config['ref']['column'] ?? null;

            if (!$refTable || !$refColumn) {
                continue;
            }

            $this->joins[] = "LEFT JOIN {$refTable} ON {$this->table}.{$column} = {$refTable}.{$refColumn}";
        }

        return $this;
    }

    public function where(string $condition, array $params = []): self
    {
        $this->wheres[] = $condition;
        $this->params = array_merge($this->params, $params);
        return $this;
    }

    public function limit(int $limit): self
    {
        $this->limit = $limit;
        return $this;
    }

    public function toSql(): string
    {
        $select = empty($this->selects) ? '*' : implode(', ', $this->selects);

        $sql = "SELECT {$select} FROM {$this->table}";

        if (!empty($this->joins)) {
            $sql .= ' ' . implode(' ', $this->joins);
        }

        if (!empty($this->wheres)) {
            $sql .= ' WHERE ' . implode(' AND ', $this->wheres);
        }

        if ($this->limit !== null) {
            $sql .= " LIMIT {$this->limit}";
        }

        return $sql;
    }

    public function getParams(): array
    {
        return $this->params;
    }
}