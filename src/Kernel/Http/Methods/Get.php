<?php

namespace Mvc\Framework\Kernel\Http\Methods;

use Mvc\Framework\Kernel\Http\JsonResponse;
use Mvc\Framework\Kernel\Model\Model;
use Mvc\Framework\Kernel\Utils\ResourceEndpoint;

readonly class Get extends ResourceEndpoint
{
    public function __construct(string $resource)
    {
        parent::__construct($resource);
    }

    public final function execute(int $id = null): array | object
    {
        if ($id) {
            try {
                return Model::getInstance()->get($this->getResource(), $id);
            } catch (\Exception $e) {
                return new JsonResponse(['message' => 'Resource not found!'], 404);
            }
        } else {
            try {
                return Model::getInstance()->getAll($this->getResource());
            } catch (\Exception $e) {
                return new JsonResponse(['message' => 'Resource not found!'], 404);
            }
        }
    }
}