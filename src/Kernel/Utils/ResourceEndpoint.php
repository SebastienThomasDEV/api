<?php

namespace Api\Framework\Kernel\Utils;

abstract readonly class ResourceEndpoint
{
    protected string $path;
    public function __construct(
        private readonly string $resource,
    ){
        $this->path = '/'. strtolower($this->resource) . 's';
    }
    public final function getResource(): string
    {
        return $this->resource;
    }

    public final function getPath(): string
    {
        return $this->path;
    }
    abstract public function execute(): array | object;







}