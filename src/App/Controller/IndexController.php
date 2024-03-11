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
    #[Endpoint(path: '/', requestMethod: 'POST')]
    public function index(Request $request, UserRepository $userRepository, PasswordHasher $passwordHasher): JsonResponse
    {
        $user = new User();
        $user->setNom($request->retrievePostValue('nom'));
        $user->setEmail($request->retrievePostValue('email'));
        $user->setMdp($passwordHasher->hash($request->retrievePostValue('mdp')));
        $user->setRoles($request->retrievePostValue('roles'));
        $user->setPrenom($request->retrievePostValue('prenom'));
        $userRepository->save($user);
        return $this->send([
            'message' => $user->getNom() . " a bien été enregistré"
        ]);
    }

    #[Endpoint(path: '/qzd', requestMethod: 'GET')]
    public function qzd(Request $request, UserRepository $userRepository, PasswordHasher $passwordHasher): JsonResponse
    {
        return $this->send([
            'message' => "toto"
        ]);
    }
}