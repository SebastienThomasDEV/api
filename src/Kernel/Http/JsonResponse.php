<?php

namespace Mvc\Framework\Kernel\Http;

class JsonResponse extends HttpResponse
{
    public function __construct(array $data = [], int $status = 200)
    {
        parent::__construct(json_encode($data), $status);
    }

}