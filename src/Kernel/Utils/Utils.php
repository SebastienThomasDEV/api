<?php

namespace Mvc\Framework\Kernel\Utils;

abstract class Utils
{

    public static function isPrimitiveFromString(string $type): bool
    {
        return match ($type) {
            'string', 'int', 'float', 'bool', 'array', 'null' => true,
            default => false,
        };
    }

    public static function getUrn(): string
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $projectDirName = explode('/', $uri)[1];
        return str_replace("/$projectDirName", '', $uri);
    }

    public static function getResourceIdentifier(): string
    {
        $urn = self::getUrn();
        $urn = explode('/', $urn);
        return $urn[1];
    }


    public static function getRequestedMethod(): string
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    public static function sanitize(string $input): string
    {
        return htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
    }



}