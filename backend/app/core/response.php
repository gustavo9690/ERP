<?php

class Response
{

    public static function json($data): void
    {

        header('Content-Type: application/json');

        echo json_encode($data);

        exit;

    }

    public static function error(string $message): void
    {

        self::json([
            "status" => "error",
            "message" => $message
        ]);

    }

}
