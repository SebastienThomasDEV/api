<?php

namespace Mvc\Framework\Kernel\Http;

class JsonResponse
{
    public function __construct(private array $data = [], private int $status = 200)
    {

    }

    public function send(): void
    {
        http_response_code($this->status);
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type');
        header('Access-Control-Max-Age: 3600');
        header('Access-Control-Allow-Credentials: true');
        print json_encode($this->data);
    }

}