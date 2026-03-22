<?php

class AuthMiddleware
{
    private static $user = null;

    public static function handle(): void
    {
        $headers = getallheaders();
        $authHeader = $headers['Authorization'] ?? null;

        if (!$authHeader) {
            self::unauthorized('Token requerido');
        }

        if (!str_starts_with($authHeader, 'Bearer ')) {
            self::unauthorized('Formato de token inválido');
        }

        $token = substr($authHeader, 7);

        try {
            $decoded = JwtHelper::validate($token);
            self::$user = $decoded->data ?? $decoded;
        } catch (Exception $e) {
            self::unauthorized('Token inválido o expirado');
        }
    }

    public static function user()
    {
        return self::$user;
    }

    private static function unauthorized(string $message): void
    {
        Response::json([
            'status'  => 'error',
            'data'    => null,
            'message' => $message
        ], 401);
        exit;
    }
}