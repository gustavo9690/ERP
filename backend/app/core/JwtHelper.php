<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtHelper
{
    private static string $secret = "GP_SYSTEMS_2026_ERP_GESTION_EMPRESA_SECRET_KEY";
    private static string $algo = "HS256";

    /* =====================================================
       GENERAR TOKEN
    ===================================================== */

    public static function generate(array $data, int $expireSeconds = null , $type='access'): string
    {
        $expireSeconds = $expireSeconds ?? Config::$jwtExpire;
        $payload = [
            "iss"  => "gp_systems",
            "iat"  => time(),
            "exp"  => time() + $expireSeconds,
            "type" => $type,
            "data" => $data
        ];

        return JWT::encode($payload, self::$secret, self::$algo);
    }

    /* =====================================================
       VALIDAR TOKEN
    ===================================================== */

    public static function validate(string $token)
    {
        try {
            return JWT::decode($token, new Key(self::$secret, self::$algo));
        } catch (\Firebase\JWT\ExpiredException $e) {
            throw new Exception("Token expirado");
        } catch (\Firebase\JWT\SignatureInvalidException $e) {
            throw new Exception("Firma inválida");
        } catch (Exception $e) {
            throw new Exception("Token inválido");
        }
    }

    /* =====================================================
       OBTENER DATA DEL TOKEN
    ===================================================== */

    public static function getData(string $token): object
    {
        $decoded = self::validate($token);

        return $decoded->data ?? new stdClass();
    }
}