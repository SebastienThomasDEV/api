<?php

namespace Api\Framework\Kernel\Abstract;
use Api\Framework\Kernel\Http\JsonResponse;


/**
 * Cette classe abstraite est utilisée pour les classes Controller de l'application.
 * Elle permet de factoriser le code des classes Controller.
 */
abstract class AbstractController
{
    /**
     * Cette méthode permet d'envoyer une réponse JSON au client.
     * Elle est final pour éviter qu'elle soit redéfinie dans les classes qui l'étendent.
     * Elle utilise la classe JsonResponse pour envoyer une réponse JSON au client.
     *
     * @param array $vars
     * @return void
     */
    public final function send(array $vars): JsonResponse
    {
        return new JsonResponse($vars, 200);
    }



}
