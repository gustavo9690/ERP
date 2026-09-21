<?php
class Cors
{
    public static function handle(): void
    {
        $allowedOrigins = [
            'http://localhost:4200',
            'http://localhost:3000',
            'http://127.0.0.1:4200',
            'http://127.0.0.1:3000'
        ];
        
        $origin = $_SERVER['HTTP_ORIGIN'] ?? '';
        
        // Permitir solo orígenes autorizados
        if (in_array($origin, $allowedOrigins)) {
            header("Access-Control-Allow-Origin: $origin");
            header("Vary: Origin");
        } else {
            // Rechazar orígenes no permitidos
            http_response_code(403);
            exit("Origen no permitido");
        }
        
        header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, PATCH, OPTIONS");
        header("Access-Control-Max-Age: 86400");
        
        // Descomenta si usas JWT con credenciales/cookies
        // header("Access-Control-Allow-Credentials: true");

        // Responder preflight
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            http_response_code(204);
            exit();
        }
    }
}