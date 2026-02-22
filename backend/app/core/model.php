<?php

abstract class Model
{
    protected PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    protected function query(string $sql, array $params = [])
    {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    protected function fetch(string $sql, array $params = [])
    {
        return $this->query($sql, $params)
                    ->fetch(PDO::FETCH_ASSOC);
    }

    protected function fetchAll(string $sql, array $params = [])
    {
        return $this->query($sql, $params)
                    ->fetchAll(PDO::FETCH_ASSOC);
    }

    protected function execute(string $sql, array $params = []): bool
    {
        return $this->query($sql, $params)
                    ->rowCount() > 0;
    }
}