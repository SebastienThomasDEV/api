<?php

namespace Mvc\Framework\Kernel\Attributes;

use Mvc\Framework\Kernel\Http\Methods\Delete;
use Mvc\Framework\Kernel\Http\Methods\Get;
use Mvc\Framework\Kernel\Http\Methods\Post;
use Mvc\Framework\Kernel\Http\Methods\Put;
use Mvc\Framework\Kernel\Utils\ResourceEndpoint;

#[\Attribute(\Attribute::TARGET_METHOD | \Attribute::TARGET_CLASS)]
class ApiResource
{

    private array $resourceEndpoints = [];

    public function __construct()
    {
    }

    public final function buildEndpoints(string $resourceName): void
    {
        $resourceName = strtolower($resourceName) . 's';
        $this->resourceEndpoints = [
            'GET' => [
                new Get(path: "/$resourceName"),
                new Get(path: "/$resourceName/"),
            ],
            'POST' => new Post(path: "/$resourceName"),
            'PUT' => new Put(path: "/$resourceName/{id}"),
            'DELETE' => new Delete(path: "/$resourceName/{id}"),
        ];
    }

    public final function getResourceEndpoints(): array
    {
        return $this->resourceEndpoints;
    }

}