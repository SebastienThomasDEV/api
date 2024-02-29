<?php

namespace Mvc\Framework\Kernel\Attributes;

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
                new ResourceEndpoint(path: "/$resourceName"),
                new ResourceEndpoint(path: "/$resourceName/{id}"),
            ],
            'POST' => new ResourceEndpoint(path: "/$resourceName"),
            'PUT' => new ResourceEndpoint(path: "/$resourceName/{id}"),
            'DELETE' => new ResourceEndpoint(path: "/$resourceName/{id}"),
        ];
    }

    public final function getResourceEndpoints(): array
    {
        return $this->resourceEndpoints;
    }

}