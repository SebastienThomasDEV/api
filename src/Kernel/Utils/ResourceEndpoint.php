<?php

namespace Mvc\Framework\Kernel\Utils;

abstract class ResourceEndpoint
{
    protected string $path = '/';
    public function __construct(
        private readonly string $resource,
        private readonly bool $protected = false
    ){
        $this->path = $this->path . $this->resource;
    }

    public final function getResource(): string
    {
        return $this->resource;
    }


    public final function isProtected(): bool
    {
        return $this->protected;
    }

    public function execute(array $vars, int $id = null): array | object {
        return [];
    }

    public final function getPath(): string
    {
        return $this->path;
    }






}