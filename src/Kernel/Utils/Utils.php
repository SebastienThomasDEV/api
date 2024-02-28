<?php

namespace Mvc\Framework\Kernel\Utils;

abstract class Utils
{

    public static function isPrimitiveFromString(string $type): bool
    {
        return match ($type) {
            'string', 'int', 'float', 'bool', 'array', 'object', 'null' => true,
            default => false,
        };
    }

    public static function getUrn(): string
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $projectDirName = explode('/', $uri)[1];
        return str_replace("/$projectDirName", '', $uri);
    }

    public static function getRequestedMethod(): string
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    public static function check($chaineDeCharacter) {
        // je la traite et le la rend
        return $chaineDeCharacter;
    }
}