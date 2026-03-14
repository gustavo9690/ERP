<?php

class AuthMiddleware
{
    private static $user = null;

    public static function handle(): void
    {
        $headers = getallheaders();
        $authHeader = $headers['Authorization'] ?? null;

        if (!$authHeader) {
            Response::json(['error' => 'Token requerido'], 401);
            exit;
        }

        $token = str_replace('Bearer ', '', $authHeader);

        try {

            $decoded = JwtHelper::validate($token);

            // guardamos el usuario autenticado
            self::$user = $decoded->data;

        } catch (Exception $e) {

            Response::json([
                'error' => 'Token inválido o expirado'
            ], 401);
            exit;

        }
    }

    public static function user()
    {
        return self::$user;
    }
}