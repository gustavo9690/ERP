<?php

class Response
{
    public static function json(
        string $status,
        $data = null,
        ?string $message = null,
        int $code = 200
    ): void {
        http_response_code($code);
        header('Content-Type: application/json; charset=utf-8');

        echo json_encode([
            'status'  => $status,
            'message' => $message,
            'data'    => $data
        ], JSON_UNESCAPED_UNICODE);

        exit;
    }

    // SUCCESS
    public static function success($data = null, string $message = 'OK', int $code = 200): void
    {
        self::json('success', $data, $message, $code);
    }

    public static function created($data = null, string $message = 'Registrado correctamente'): void
    {
        self::json('success', $data, $message, 201);
    }

    public static function noContent(): void
    {
        http_response_code(204);
        exit;
    }

    // ERRORS
    public static function error(string $message = 'Error', int $code = 400, $data = null): void
    {
        self::json('error', $data, $message, $code);
    }

    public static function unauthorized(string $message = 'No autorizado'): void
    {
        self::json('error', null, $message, 401);
    }

    public static function forbidden(string $message = 'Acceso denegado'): void
    {
        self::json('error', null, $message, 403);
    }

    public static function notFound(string $message = 'No encontrado'): void
    {
        self::json('error', null, $message, 404);
    }

    public static function unprocessable(string $message = 'Error de validación', $data = null): void
    {
        self::json('error', $data, $message, 422);
    }

    public static function serverError(string $message = 'Error interno del servidor'): void
    {
        self::json('error', null, $message, 500);
    }
}