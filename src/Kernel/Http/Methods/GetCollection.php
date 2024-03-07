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
        try {
            return new JsonResponse(Model::getInstance()->getAll($this->getResource()));
        } catch (\Exception $e) {
            return new JsonResponse(['message' => 'Resource not found!'], 404);
        }
    }
}