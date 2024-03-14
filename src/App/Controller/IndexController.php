<?php

namespace Api\Framework\App\Controller;


use Api\Framework\App\Repository\UserRepository;
use Api\Framework\Kernel\Abstract\AbstractController;
use Api\Framework\Kernel\Attributes\Endpoint;
use Api\Framework\Kernel\Http\JsonResponse;

class IndexController extends AbstractController
{
    #[Endpoint(path: '/', requestMethod: 'GET')]
    public function index(UserRepository $userRepository): JsonResponse
    {
        $users = $userRepository->findAll();
        return $this->send([
            'message' => $users
        ]);
    }
}