<?php

namespace Mvc\Framework\Kernel\Exception;

use Mvc\Framework\Kernel\Http\JsonResponse;

class ExceptionManager
{

    public static function send(\Throwable $e): JsonResponse
    {
        $vars = [
            'message' => $e->getMessage(),
            'code' => $e->getCode()
        ];
        return new JsonResponse($vars, 500);
    }

}