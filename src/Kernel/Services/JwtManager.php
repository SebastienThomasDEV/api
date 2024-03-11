<?php

namespace Api\Framework\Kernel\Services;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtManager
{

    static string $privateKey;
    static string $publicKey;

    public function __construct()
    {
        date_default_timezone_set('UTC');
        self::$privateKey = file_get_contents(__DIR__ . '/../../../config/jwt/private.pem');
        self::$publicKey = file_get_contents(__DIR__ . '/../../../config/jwt/public.pem');
    }

    public static function encode(array $data): string
    {
        return JWT::encode($data, self::$privateKey, 'RS256');
    }

    public static function decode(string $token): array
    {
        return (array)JWT::decode($token, new Key(self::$publicKey, 'RS256'));
    }

}