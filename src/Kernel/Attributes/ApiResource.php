<?php

namespace Mvc\Framework\Kernel\Attributes;


use Mvc\Framework\Kernel\Http\Methods\Patch;
use Mvc\Framework\Kernel\Http\Methods\Delete;
use Mvc\Framework\Kernel\Http\Methods\Get;
use Mvc\Framework\Kernel\Http\Methods\GetCollection;
use Mvc\Framework\Kernel\Http\Methods\Post;
use Mvc\Framework\Kernel\Http\Methods\Put;

#[\Attribute(\Attribute::TARGET_METHOD | \Attribute::TARGET_CLASS)]
class ApiResource
{

    private array $resourceEndpoints = [];

    public function __construct(
        private string $resourceName
    )
    {
        $this->resourceName = strtolower($this->resourceName);
        $this->buildEndpoints($this->resourceName);
    }

    public final function buildEndpoints(string $resourceName): void
    {
        $resourceName = strtolower($resourceName);
        $this->resourceEndpoints = [
            'GET' => [
                new Get(resource: $resourceName),
                new GetCollection(resource: $resourceName),
            ],
            'POST' => new Post(resource: $resourceName),
            'PUT' => new Put(resource: $resourceName),
            'DELETE' => new Delete(resource: $resourceName),
            'PATCH' => new Patch(resource: $resourceName)
        ];
    }

    public final function getResourceEndpoints(): array
    {
        return $this->resourceEndpoints;
    }

}