<?php
class Cors
{
    public static function handle(): void
    {
        $allowedOrigins = [
            'http://localhost:4200',
            'http://127.0.0.1:4200',
            'http://localhost:3000',
            'http://127.0.0.1:3000'
        ];
        
        $origin = $_SERVER['HTTP_ORIGIN'] ?? '';
        
        // Solo validar si viene un origen (desde frontend)
        if ($origin !== '') {
            if (!in_array($origin, $allowedOrigins)) {
                http_response_code(403);
                echo "Origen no permitido: $origin";
                exit;
            }
            header("Access-Control-Allow-Origin: $origin");
            header("Vary: Origin");
        }
        
        header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, PATCH, OPTIONS");
        header("Access-Control-Max-Age: 86400");

        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            http_response_code(204);
            exit();
        }
    }
}