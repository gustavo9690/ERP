<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtHelper
{
    public static function generate(array $data): string
    {
        $payload = [
            'iss' => Config::$jwt_issuer,
            'iat' => time(),
            'exp' => time() + Config::$jwt_expire,
            'data' => $data
        ];

        return JWT::encode($payload, Config::$jwt_secret, 'HS256');
    }

    public static function validate(string $token)
    {
        return JWT::decode($token, new Key(Config::$jwt_secret, 'HS256'));
    }
}