<?php

class Database
{

    private static ?Database $instance = null;

    private PDO $connection;

    private function __construct()
    {

        $this->connection = new PDO(
            "mysql:host=localhost;dbname=gp_systems",
            "root",
            "root"
        );

        $this->connection->setAttribute(
            PDO::ATTR_ERRMODE,
            PDO::ERRMODE_EXCEPTION
        );

    }

    public static function getInstance(): Database
    {

        if (self::$instance === null)
        {
            self::$instance = new Database();
        }

        return self::$instance;

    }

    public function getConnection(): PDO
    {
        return $this->connection;
    }

}
