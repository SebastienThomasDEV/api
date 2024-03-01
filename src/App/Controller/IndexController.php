<?php

namespace Mvc\Framework\App\Controller;

use Mvc\Framework\Kernel\AbstractController;
use Mvc\Framework\Kernel\Attributes\Endpoint;
use Mvc\Framework\Kernel\Services\Request;
use Mvc\Framework\Kernel\Services\Serializer;

class IndexController extends AbstractController
{
    #[Endpoint(path: '/', name: 'index', requestMethod: 'GET')]
    public function create(Serializer $serializer, Request $request)
    {


        $this->send([
            'message' => 'Welcome to the simplefony API'
        ]);

    }
}