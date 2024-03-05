<?php

namespace Mvc\Framework\Kernel\Http\Methods;

use Mvc\Framework\Kernel\Model\Model;
use Mvc\Framework\Kernel\Utils\ResourceEndpoint;

class GetCollection extends ResourceEndpoint
{
    public function __construct(
        private string $resource
    ) {
        parent::__construct($this->resource, 'GET');
    }

    public final function execute(array $vars, int $id = null): array | object
    {
        return Model::getInstance()->getAll($this->getResource());
    }
}