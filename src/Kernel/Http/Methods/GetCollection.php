<?php

namespace Mvc\Framework\Kernel\Http\Methods;

use Mvc\Framework\Kernel\Http\JsonResponse;
use Mvc\Framework\Kernel\Model\Model;
use Mvc\Framework\Kernel\Utils\ResourceEndpoint;

class GetCollection extends ResourceEndpoint
{
    public function __construct(
        string $resource,
        bool $protected = false
    ) {
        parent::__construct($resource, $protected);
        $this->path = $this->path . 's';
    }

    public final function execute(): array | object
    {
        return new JsonResponse(Model::getInstance()->getAll($this->getResource()));
    }
}