<?php

namespace Mvc\Framework\Kernel\Attributes;


use Mvc\Framework\Kernel\Http\Methods\Patch;
use Mvc\Framework\Kernel\Http\Methods\Delete;
use Mvc\Framework\Kernel\Http\Methods\Get;
use Mvc\Framework\Kernel\Http\Methods\Post;
use Mvc\Framework\Kernel\Http\Methods\Put;

#[\Attribute(\Attribute::TARGET_METHOD | \Attribute::TARGET_CLASS)]
class ApiResource
{

    private array $resourceEndpoints = [];

    public function __construct(
        private readonly string $resourceName
    )
    {
        $this->resourceEndpoints = [
            'GET' => new Get($this->resourceName),
            'POST' => new Post($this->resourceName),
            'PUT' => new Put($this->resourceName),
            'PATCH' => new Patch($this->resourceName),
            'DELETE' => new Delete($this->resourceName)
        ];
    }

    public final function getResourceEndpoints(): array
    {
        return $this->resourceEndpoints;
    }

}