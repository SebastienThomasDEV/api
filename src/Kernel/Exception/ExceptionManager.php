<?php

namespace Mvc\Framework\Kernel\Exception;

use Mvc\Framework\Kernel\Http\JsonResponse;

class ExceptionManager
{

    public static function send(\Throwable $e): void
    {
        $vars = [
            'message' => $e->getMessage(),
            'code' => $e->getCode()
        ];
        $response = new JsonResponse($vars, 500);
        $response->send();
        die();
    }

}