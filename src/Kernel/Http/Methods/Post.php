<?php

namespace Mvc\Framework\Kernel\Http\Methods;

use Mvc\Framework\Kernel\Http\JsonResponse;
use Mvc\Framework\Kernel\Model\Model;
use Mvc\Framework\Kernel\Utils\ResourceEndpoint;
use Mvc\Framework\Kernel\Utils\Utils;

readonly class Post extends ResourceEndpoint
{

    public function __construct(string $resource)
    {
        parent::__construct($resource);
    }

    public final function execute(): array | object
    {
        $vars = Utils::getRequestBody();
        try {
            $sanitizedData = Utils::sanitizeData($vars);
            Model::getInstance()->post($this->getResource(), $sanitizedData);
            return new JsonResponse(['message' => 'Resource created successfully!']);
        } catch (\Exception $e) {
            return new JsonResponse(['message' => 'Resource could not be created!'], 500);
        }
    }
}