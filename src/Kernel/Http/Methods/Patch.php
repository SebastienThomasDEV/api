<?php

namespace Mvc\Framework\Kernel\Http\Methods;

use Mvc\Framework\Kernel\Http\JsonResponse;
use Mvc\Framework\Kernel\Model\Model;
use Mvc\Framework\Kernel\Utils\ResourceEndpoint;
use Mvc\Framework\Kernel\Utils\Utils;

class Patch extends ResourceEndpoint
{

    public function __construct(string $resource, bool $protected = false)
    {
        parent::__construct($resource, $protected);
        $this->path .= 's/{id}';
    }

    public final function execute(array $vars = null, int $id = null): array | object
    {
        try {
            $sanitizedData = Utils::sanitizeData($vars);
            Model::getInstance()->patch($this->getResource(), $id, $sanitizedData);
            return new JsonResponse(['message' => 'Resource patched successfully!', 'id' => $id]);
        } catch (\Exception $e) {
            return new JsonResponse(['message' => 'Resource not found!'], 404);
        }
    }

}