<?php

namespace Api\Framework\Kernel\Abstract;

abstract class AbstractService
{
    public function __construct(
        string $name
    )
    {
    }

    public final function getName(): string
    {
        return $this->name;
    }

}