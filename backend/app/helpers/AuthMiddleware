<?php
class AuthMiddleware
{
    public static function handle(): void
    {
        $headers = getallheaders();
        $authHeader = $headers['Authorization'] ?? '';

        if (!$authHeader) {
            Response::json(['message' => 'Token requerido'], 401);
        }

        $token = str_replace('Bearer ', '', $authHeader);

        try {
            JwtHelper::validate($token);
        } catch (Exception $e) {
            Response::json(['message' => 'Token inválido o expirado'], 401);
        }
    }
}