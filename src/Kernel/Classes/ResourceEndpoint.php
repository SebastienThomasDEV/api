<?php

namespace Mvc\Framework\Kernel\Classes;

class ResourceEndpoint
{
    private int $identifier;
    public function __construct(
        private readonly string $path,
        private bool $protected = false
    ){}

    public final function getPath(): string
    {
        return $this->path;
    }


    public final function isProtected(): bool
    {
        return $this->protected;
    }

    public function execute(): void
    {
        echo "Executing the endpoint";
    }






}