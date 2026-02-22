<?php

class Config
{
    // JWT
    public static string $jwt_secret = "GP_SYSTEM_SECRET_2026";
    public static string $jwt_issuer = "gp-system";
    public static int $jwt_expire = 3600; // 1 hora

    // Entorno
    public static string $env = 'development';

    // URL base
    public static string $baseUrl = 'http://localhost/erp/';

    // Controlador y método por defecto
    public static string $defaultController = 'Welcome';
    public static string $defaultMethod = 'index';

    // Zona horaria
    public static string $timezone = 'America/Lima';

    // Mostrar errores
    public static bool $showErrors = true;

    public static function init(): void
    {
        date_default_timezone_set(self::$timezone);

        if (self::$showErrors) {
            ini_set('display_errors', 1);
            error_reporting(E_ALL);
        } else {
            ini_set('display_errors', 0);
            error_reporting(0);
        }
    }
}

