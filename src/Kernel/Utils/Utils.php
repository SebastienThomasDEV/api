<?php

namespace Api\Framework\Kernel\Utils;

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


    public static function getRequestIdentifier(): int | null
    {
        $urn = self::getUrn();
        $urn = explode('/', $urn);
        return $urn[2] ?? null;
    }


    public static function getRequestedMethod(): string
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    public static function sanitizeData(array $data): array
    {
        $sanitizedData = [];
        foreach ($data as $key => $value) {
            $value = trim($value);
            $value = stripslashes($value);
            $value = htmlspecialchars($value);
            $sanitizedData[$key] = $value;
        }
        return $sanitizedData;
    }

    public static function getRequestBody(): array | null
    {
        $rawInput = fopen('php://input', 'r');
        $tempStream = fopen('php://temp', 'r+');
        stream_copy_to_stream($rawInput, $tempStream);
        rewind($tempStream);
        $requestBody = file_get_contents('php://input');
        return json_decode($requestBody, true) ?? [];
    }



}