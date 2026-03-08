<?php
class Response
{

    public static function json($status, $data = null, $message = null, $code = 200): void
    {
        http_response_code($code);
        header('Content-Type: application/json');

        echo json_encode([
            "status" => $status,
            "data" => $data,
            "message" => $message
        ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

        exit;
    }

    public static function success($data = null, $message = "OK", $code = 200)
    {
        self::json("success", $data, $message, $code);
    }

    public static function error($message = "Error", $code = 400)
    {
        self::json("error", null, $message, $code);
    }

}