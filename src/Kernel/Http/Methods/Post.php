<?php

namespace Mvc\Framework\Kernel\Http\Methods;

use Mvc\Framework\Kernel\Http\JsonResponse;
use Mvc\Framework\Kernel\Model\Model;
use Mvc\Framework\Kernel\Utils\ResourceEndpoint;
use Mvc\Framework\Kernel\Utils\Utils;

class Post extends ResourceEndpoint
{

    public function __construct(string $resource, bool $protected = false)
    {
        parent::__construct($resource, $protected);
        $this->path = $this->path . 's';
    }

    public final function execute(array $vars = null, int $id = null): array | object
    {
        try {
            $sanitizedData = Utils::sanitizeData($vars);
            Model::getInstance()->post($this->getResource(), $sanitizedData);
            return new JsonResponse(['message' => 'Resource created successfully!']);
        } catch (\Exception $e) {
            return new JsonResponse(['message' => 'Resource could not be created!'], 500);
        }
    }
}