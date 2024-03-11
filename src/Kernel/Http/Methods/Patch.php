<?php

namespace Mvc\Framework\Kernel\Http\Methods;

use Mvc\Framework\Kernel\Http\JsonResponse;
use Mvc\Framework\Kernel\Model\Model;
use Mvc\Framework\Kernel\Utils\ResourceEndpoint;
use Mvc\Framework\Kernel\Utils\Utils;

readonly class Patch extends ResourceEndpoint
{

    public function __construct(string $resource)
    {
        parent::__construct($resource);
    }

    public final function execute(int $id = null): array | object
    {
        try {
            $vars = Utils::getRequestBody();
            $sanitizedData = Utils::sanitizeData($vars);
            Model::getInstance()->patch($this->getResource(), $id, $sanitizedData);
            return new JsonResponse(['message' => 'Resource patched successfully!', 'id' => $id]);
        } catch (\Exception $e) {
            return new JsonResponse(['message' => 'Resource not found!'], 404);
        }
    }

}