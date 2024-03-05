<?php

namespace Mvc\Framework\Kernel\Services;
class Serializer
{
    public final function serialize(mixed $entity): array
    {
        $array = (array)$entity;
        $json = [];
        foreach ($array as $key => $value) {
            $key = str_replace("\0", "", $key);
            $key = str_replace(get_class($entity), "", $key);
            $json[$key] = $value;
        }
        return $json;
    }
}