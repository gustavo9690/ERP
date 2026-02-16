<?php

class Database
{

    private static string $host = 'localhost';
    private static string $dbname = 'gp_systems';
    private static string $username = 'root';
    private static string $password = 'root';
    //private static string $charset = 'utf8mb4';

    public static function connect(): PDO
    {
        try {

            $dsn = "mysql:host=" . self::$host . ";dbname=" . self::$dbname; //";charset=" . self::$charset;

            $pdo = new PDO($dsn, self::$username, self::$password);

            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

            $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

            return $pdo;

        } catch (PDOException $e) {

            die("Error de conexión: " . $e->getMessage());

        }
    }

}
