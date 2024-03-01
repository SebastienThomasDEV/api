<?php

namespace Mvc\Framework\Kernel\Utils;

abstract class ResourceEndpoint
{
    protected ?int $identifier = null;
    public function __construct(
        private readonly string $path,
        private readonly bool $protected = false
    ){}

    public final function getPath(): string
    {
        return $this->path;
    }


    public final function isProtected(): bool
    {
        return $this->protected;
    }

    protected function execute(): void{}






}