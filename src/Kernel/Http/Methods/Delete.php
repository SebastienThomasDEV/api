<?php

namespace Mvc\Framework\Kernel\Http\Methods;

use Mvc\Framework\Kernel\Http\JsonResponse;
use Mvc\Framework\Kernel\Model\Model;
use Mvc\Framework\Kernel\Utils\ResourceEndpoint;

class Delete extends ResourceEndpoint
{

    public function __construct(string $resource, bool $protected = false)
    {
        parent::__construct($resource, $protected);
        $this->path = $this->path . 's/{id}';
    }

    public final function execute(int $id = null): array | object
    {
        try {
            Model::getInstance()->delete($this->getResource(), $id);
            return new JsonResponse(['message' => 'Resource deleted successfully!', 'id' => $id]);
        } catch (\Exception $e) {
            return new JsonResponse(['message' => 'Resource not found!'], 404);
        }
    }

}