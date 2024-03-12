<?php

namespace Api\Framework\App\Controller;


use Api\Framework\App\Entity\User;
use Api\Framework\App\Repository\UserRepository;
use Api\Framework\Kernel\AbstractController;
use Api\Framework\Kernel\Attributes\Endpoint;
use Api\Framework\Kernel\Http\JsonResponse;
use Api\Framework\Kernel\Services\PasswordHasher;
use Api\Framework\Kernel\Services\Request;

class IndexController extends AbstractController
{

    // On définit une route pour la méthode index
    // On définit le chemin de la route
    // On définit la méthode HTTP associée à la route
    // On définit le nom de la méthode qui sera appelée pour répondre à la requête
    // On peut aussi injecter des dépendances dans les paramètres de la méthode
    #[Endpoint(path: '/', requestMethod: 'GET')]
    public function index(Request $request, UserRepository $userRepository, PasswordHasher $passwordHasher): JsonResponse
    {

        // on envoie une réponse JSON au client
        return $this->send([
            'message' => 'ok'
        ]);
    }
}