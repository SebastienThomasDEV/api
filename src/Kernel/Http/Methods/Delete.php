<?php

namespace Mvc\Framework\Kernel\Http\Methods;

use Mvc\Framework\Kernel\Utils\ResourceEndpoint;

class Delete extends ResourceEndpoint
{

    public function __construct(string $path, bool $protected = false)
    {
        parent::__construct($path, $protected);
    }

}