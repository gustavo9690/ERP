<?php

class Config
{
    // Entorno
    public static string $env = 'development';
    public static bool $showErrors = true;

    // App
    public static string $baseUrl = 'http://localhost/erp/';
    public static string $timezone = 'America/Lima';
    public static string $charset = 'UTF-8';

    // Ruta por defecto
    public static string $defaultModule = 'seguridad';
    public static string $defaultSubmodule = 'roles';
    public static string $defaultController = 'Roles';
    public static string $defaultMethod = 'obtener';

    // JWT
    public static string $jwtSecret = 'GP_SYSTEM_SECRET_2026';
    public static string $jwtIssuer = 'gp-system';
    public static int $jwtExpire = 1000;

    // Database
    public static string $dbHost = 'localhost';
    public static string $dbName = 'gp_systems';
    public static string $dbUser = 'root';
    public static string $dbPassword = 'root';
    public static string $dbCharset = 'utf8mb4';

    public static function init(): void
    {
        date_default_timezone_set(self::$timezone);
        ini_set('default_charset', self::$charset);

        if (self::$env === 'development' || self::$showErrors) {
            ini_set('display_errors', '1');
            error_reporting(E_ALL);
        } else {
            ini_set('display_errors', '0');
            error_reporting(0);
        }
    }
}