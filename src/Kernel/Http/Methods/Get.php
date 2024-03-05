<?php

namespace Mvc\Framework\Kernel\Http\Methods;

use Mvc\Framework\Kernel\Model\Model;
use Mvc\Framework\Kernel\Utils\ResourceEndpoint;

class Get extends ResourceEndpoint
{

    public function __construct(string $resource, bool $protected = false)
    {
        parent::__construct($resource, $protected);
        $this->path = $this->path . '/{id}';
    }

    public final function execute(array $vars, int $id = null): array | object
    {
        return Model::getInstance()->get($this->getResource(), $id);
    }
}