<?php

namespace Api\Framework\Kernel\Attributes;


use Api\Framework\Kernel\Http\Methods\Patch;
use Api\Framework\Kernel\Http\Methods\Delete;
use Api\Framework\Kernel\Http\Methods\Get;
use Api\Framework\Kernel\Http\Methods\Post;
use Api\Framework\Kernel\Http\Methods\Put;

#[\Attribute(\Attribute::TARGET_METHOD | \Attribute::TARGET_CLASS)]
class ApiResource
{

    private array $resourceEndpoints = [];

    public function __construct(
        private readonly string $resourceName,
        private readonly ?Get $get = null,
        private readonly ?Put $put = null,
        private readonly ?Post $post = null,
        private readonly ?Patch $patch = null,
        private readonly ?Delete $delete = null
    )
    {
        $this->resourceEndpoints = [
            'GET'       => new Get($resourceName),
            'POST'      => new Post($resourceName),
            'PUT'       => new Put($resourceName),
            'PATCH'     => new Patch($resourceName),
            'DELETE'    => new Delete($resourceName)
        ];
    }

    public final function getResourceEndpoints(): array
    {
        return $this->resourceEndpoints;
    }

    public final function getResourceName(): string
    {
        return $this->resourceName;
    }

}