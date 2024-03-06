<?php

namespace Mvc\Framework\Kernel;
use Mvc\Framework\Kernel\Http\JsonResponse;


// Cette classe abstraite est utilisée pour les classes Controller de l'application.
// Elle permet de factoriser le code des classes Controller.
// Elle possède une méthode send qui permet d'envoyer une réponse JSON au client.
// Cette méthode est final pour éviter qu'elle soit redéfinie dans les classes qui l'étendent.
// Elle utilise la classe JsonResponse pour envoyer une réponse JSON au client. (voir le fichier JsonResponse.php)
abstract class AbstractController
{
    /**
     * send a JSON response to the client with the given data as an array
     *
     * @param array $vars
     * @return void
     */
    public final function send(array $vars): JsonResponse
    {
        try {
            return new JsonResponse($vars, 200);
        } catch (\Exception $e) {
            $vars = [
                'message' => $e->getMessage(),
                'code' => $e->getCode()
            ];
            return new JsonResponse($vars, 500);
        }
    }


}
