<?php

namespace Api\Framework\Kernel\Utils;
use Api\Framework\Kernel\Exception\ExceptionManager;

abstract class Serializer
{
    public final static function serialize(mixed $data, string $class): object | null
    {
        try {
            $class = new \ReflectionClass($_ENV["NAMESPACE"].'\\App\\Entity\\'. $class);
            $properties = $class->getProperties();
            $object = $class->newInstance();
            foreach ($properties as $property) {
                $property->setAccessible(true);
                $property->setValue($object, $data[$property->getName()]);
            }
            return $object;
        } catch (\ReflectionException $e) {
            return ExceptionManager::send($e);
        }
    }

    public final static function serializeAll(array $data, string $class): array
    {
        $result = [];
        foreach ($data as $item) {
            $result[] = self::serialize($item, $class);
        }
        return $result;
    }
}