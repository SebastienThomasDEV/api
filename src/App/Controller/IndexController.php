<?php

namespace Api\Framework\App\Controller;


use Api\Framework\Kernel\Abstract\AbstractController;
use Api\Framework\Kernel\Attributes\Endpoint;
use Api\Framework\Kernel\Http\JsonResponse;

class IndexController extends AbstractController
{
    #[Endpoint(path: '/', requestMethod: 'GET')]
    public function index(): JsonResponse
    {

        $test = "test";
        return $this->send([
            'tutu' => $test,
            'message' => "à vous de jouer"
        ]);
    }
}