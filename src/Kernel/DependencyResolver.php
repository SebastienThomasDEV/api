<?php

namespace Mvc\Framework\Kernel;

abstract class DependencyResolver
{
    private static array $services = [];

    public static function resolve(array $parameters): array
    {
        foreach ($parameters as $key => $file_path) {
            try {
                $class = new \ReflectionClass($file_path);
                $class = $class->newInstance();
                self::$services[$key] = $class;
            } catch (\ReflectionException $e) {
                echo $e->getMessage();
            }
        }
        return self::$services;
    }

}