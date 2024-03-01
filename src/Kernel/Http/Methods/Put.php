<?php

namespace Mvc\Framework\Kernel\Http\Methods;

use Mvc\Framework\Kernel\Model\Model;
use Mvc\Framework\Kernel\Utils\ResourceEndpoint;

class Put extends ResourceEndpoint
{

    public function __construct(string $resource, bool $protected = false)
    {
        parent::__construct($resource, $protected);
    }

    protected function execute(): void
    {
        Model::getInstance()->update($this->identifier, );
    }
}