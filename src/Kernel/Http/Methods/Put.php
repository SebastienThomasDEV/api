<?php

namespace Mvc\Framework\Kernel\Http\Methods;

use Mvc\Framework\Kernel\Http\JsonResponse;
use Mvc\Framework\Kernel\Model\Model;
use Mvc\Framework\Kernel\Utils\ResourceEndpoint;

class Put extends ResourceEndpoint
{

    public function __construct(string $resource, bool $protected = false)
    {
        parent::__construct($resource, $protected);
        $this->path = $this->path . '/{id}';
    }

    public final function execute(array $vars, int $id = null): array | object
    {
        Model::getInstance()->update($this->getResource(), $id, $vars);
        return new JsonResponse(['message' => 'Resource updated successfully!', 'id' => $id]);
    }
}