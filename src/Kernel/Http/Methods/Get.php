<?php

namespace Mvc\Framework\Kernel\Http\Methods;

use Mvc\Framework\Kernel\Utils\ResourceEndpoint;

class Get extends ResourceEndpoint
{

    public function __construct(string $path, bool $protected = false)
    {
        parent::__construct($path, $protected);
    }

    protected function execute(): void
    {
        echo "Executing GET method";
    }

}