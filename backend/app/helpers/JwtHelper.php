<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtHelper
{
    private static string $secret = "GP_SYSTEMS_2026_ERP_GESTION_EMPRESA_SECRET_KEY";
    private static string $algo = "HS256";

    public static function generate(array $data): string
    {
        $payload = [
            "iss" => "gp_systems",
            "iat" => time(),
            "exp" => time() + (60 * 60 * 4), // 4 horas
            "data" => $data
        ];

        return JWT::encode($payload, self::$secret, self::$algo);
    }

    public static function validate(string $token)
    {
        try {
            return JWT::decode($token, new Key(self::$secret, self::$algo));
        } catch (Exception $e) {
            return null;
        }
    }
}