<?php

namespace Mvc\Framework\Kernel;

use Mvc\Framework\Kernel\Authentication\Guard;
use Mvc\Framework\Kernel\Exception\ExceptionManager;
use Mvc\Framework\Kernel\Utils\Utils;


// Cette classe est un routeur qui permet de gérer les requêtes entrantes dans notre application
// Elle permet de charger les routes de notre application et de rediriger la requête de l'utilisateur vers le bon contrôleur
class ApiRouter
{

    // on déclare une propriété privée et statique pour stocker une liste de nos endpoints
    // un endpoint est une classe qui contient les informations d'une route (path, méthode, controller, etc.) (voir le fichier Endpoint.php dans le dossier Kernel/Attributes)
    // on déclare cette propriété comme étant un tableau vide pour stocker nos endpoints plus tard
    private static array $controllerEndpoints = [];

    // on déclare une propriété privée et statique pour stocker une liste de nos resources des entités
    // une resource est une classe qui contient les informations d'une entité (voir le fichier ApiResource.php dans le dossier Kernel/Attributes)
    // on annotera les entités avec cette classe pour générer les routes de l'API automatiquement
    // une annotation est un moyen de rajouter des métadonnées à notre code pour signaler des informations supplémentaires
    // on déclare cette propriété comme étant un tableau vide pour stocker nos resources plus tard
    private static array $resources = [];



    // on déclare une méthode statique et publique pour enregistrer les endpoints de nos contrôleurs
    // dans cette méthode, on va parcourir le dossier Controller de notre application pour lire les fichiers qu'il contient
    // car chaque fichier correspond à un contrôleur si on respecte la convention de nommage (NomDuController.php)
    // grace à la classe ReflectionClass, on va lire les metadonnées de nos classes pour récupérer les attributs de type Endpoint
    // une metadonnée est une information qui décrit les données d'une autre information (par exemple, les attributs d'une classe)
    // la classe ReflectionClass permet de lire les métadonnées d'une classe (attributs, méthodes, etc.) et de les manipuler en PHP
    // en gros lire la signature de la classe et comment elle est construite
    public static function registerControllerEndpoints(): void
    {
        // j'ouvre le dossier Controller de mon application pour lire les fichiers qu'il contient (les contrôleurs)
        // avec la fonction opendir de PHP qui permet d'ouvrir un dossier
        $dir = opendir(__DIR__ . '/../../src/App/Controller');
        // je fait une boucle "tant que" pour parcourir les fichiers du dossier Controller
        // grace à la fonction readdir de PHP qui permet de lire le contenu d'un dossier
        // donc tant que je peux lire un fichier, je le stocke dans la variable $file_path
        // sinon, je sors de la boucle
        while ($file_path = readdir($dir)) {
            // 1. je vérifie si le fichier n'est pas un dossier (.) ou (..) car readdir lit aussi les dossiers
            // donc si le fichier n'est pas un dossier, je continue
            if ($file_path !== '.' && $file_path !== '..') {
                // 2. je remplace l'extension .php de mon fichier par une chaine vide
                // car je veux récupérer le nom de ma classe sans l'extension .php qui m'intéresse pas
                // j'ai besoin du nom de la classe pour créer une nouvelle instance de ma classe (XxxController.php) grace à la classe ReflectionClass
                // la classe ReflectionClass nous offre la possibilité d'instancier une classe dynamiquement en PHP
                $file_path = str_replace('.php', '', $file_path);
                // je concatène le namespace de mon controller avec le nom de la classe
                // car je veux instancier ma classe avec son namespace complet (Mvc\Framework\App\Controller\XxxController)
                // ce namespace complet est nécessaire pour instancier ma classe avec la classe ReflectionClass
                $file_path = 'Mvc\\Framework\\App\\Controller\\' . $file_path;
                // j'essaye d'instancier ma classe avec la classe ReflectionClass
                // et j'encadre cette instruction avec un bloc try/catch
                try {
                    // je crée une instance de la classe ReflectionClass avec le namespace complet de ma classe
                    // ce qui me permet de lire les métadonnées de ma classe passé en paramètre
                    $class = new \ReflectionClass($file_path);
                    // Au vu du fait que je lire les attributs des methodes de ma classe, je dois d'abord récupérer les méthodes de ma classe
                    // je recupère donc les méthodes contenu de ma classe grace à la méthode getMethods de la classe ReflectionClass
                    // ce qu'il faut savoir c'est la ReflectionClass n'est pas une instance de ma classe, il ne fournit que des informations sur la classe
                    $methods = $class->getMethods();
                    // je boucle donc sur les méthodes de ma classe
                    foreach ($methods as $method) {
                        // Vu que je veux lire les attributs de mes méthodes
                        // je récupère les attributs de ma méthode grace à la méthode getAttributes de la classe ReflectionMethod
                        // car je veux lire les attributs de mes méthodes pour récupérer les attributs de type Endpoint
                        // je stocke les attributs de ma méthode dans une variable $attributes
                        $attributes = $method->getAttributes();
                        // je récupère les paramètres de ma méthode grace à la méthode getParameters de la classe ReflectionMethod
                        // car je veux lire les paramètres de mes méthodes pour récupérer les types des paramètres
                        // qui seront utilisés pour les injections de dépendances (Voir DependencyResolver.php)
                        // l'injection de dépendances est une technique qui permet de passer des dépendances à une classe
                        // si votre contrôleur a besoin d'une dépendance (un service, un repository, etc.) pour accomplir une tâche
                        // vous pouvez l'injecter dans les paramètres de votre méthode pour l'utiliser
                        // ce service sera instancié automatiquement par notre conteneur de services (DependencyResolver.php)
                        // qui utilise lui aussi le système de réflexion de PHP
                        $parameters = $method->getParameters();
                        // je boucle sur les attributs de ma méthode
                        foreach ($attributes as $attribute) {
                            // je vérifie si l'attribut de ma méthode est de type Endpoint
                            if ($attribute->getName() === 'Mvc\\Framework\\Kernel\\Attributes\\Endpoint') {
                                // si c'est le cas, je crée une instance de mon attribut Endpoint
                                $endpoint = $attribute->newInstance();
                                // je set le controller de mon endpoint avec le chemin de mon fichier
                                $endpoint->setController($file_path);
                                // je set la méthode de mon endpoint avec le nom de ma méthode
                                $endpoint->setMethod($method->getName());
                                // je boucle sur les paramètres de ma méthode
                                foreach ($parameters as $parameter) {
                                    // je vérifie si le type du paramètre n'est pas un type primitif
                                    if (!Utils::isPrimitiveFromString($parameter->getType())) {
                                        $endpoint->setParameter($parameter->getName(), $parameter->getType());
                                    }
                                }
                                // je stocke mon endpoint dans un tableau
                                self::$controllerEndpoints[] = $endpoint;
                            }
                        }
                    }
                } catch (\ReflectionException $e) {
                    ExceptionManager::send(new \Exception($e->getMessage(), $e->getCode()));
                }
            }
        }
        closedir($dir);
    }

    public static function registerResourceEndpoints(): void {
        $dir = opendir(__DIR__ . '/../../src/App/Entity');
        while ($file_path = readdir($dir)) {
            if ($file_path !== '.' && $file_path !== '..') {
                $className = str_replace('.php', '', $file_path);
                $file_path = 'Mvc\\Framework\\App\\Entity\\' . $className;
                try {
                    $class = new \ReflectionClass($file_path);
                    $attributes = $class->getAttributes();
                    foreach ($attributes as $attribute) {
                        if ($attribute->getName() === 'Mvc\\Framework\\Kernel\\Attributes\\ApiResource') {
                            $resource = $attribute->newInstance();
                            $resource->buildEndpoints($className);
                            self::$resources[$className] = $resource->getResourceEndpoints();
                        }
                    }
                } catch (\ReflectionException $e) {
                    ExceptionManager::send(new \Exception($e->getMessage(), $e->getCode()));
                }
            }
        }
        closedir($dir);
                            dd(self::$resources);
    }
    private static function load(): void
    {
        $endpointFound = null;
        foreach (self::$controllerEndpoints as $endpoint) {
            if ($endpoint->getPath() === Utils::getUrn()) {
                if ($endpoint->getRequestMethod() === Utils::getRequestedMethod()) {
                    if ($endpoint->isProtected()) {
                        if (Guard::check()) {
                            $endpointFound = $endpoint;
                        } else {
                            ExceptionManager::send(new \Exception('Unauthorized access, invalid token', 401));
                        }
                    } else {
                        $endpointFound = $endpoint;
                    }
                } else {
                    ExceptionManager::send(new \Exception('Method not allowed for this endpoint', 405));
                }
            }
        }
        if (!$endpointFound) {
            ExceptionManager::send(new \Exception('Endpoint not found in your project, it does match with the requested path', 404));
        } else {
            if (class_exists($endpointFound->getController())) {
                $controller = new \ReflectionClass($endpointFound->getController());
                try {
                    $controller = $controller->newInstance();
                    if (method_exists($controller, $endpointFound->getMethod())) {
                        $method = $endpointFound->getMethod();
                        $services = DependencyResolver::resolve($endpointFound->getParameters());
                        $controller->$method(...$services);
                    }
                } catch (\ReflectionException $e) {
                    ExceptionManager::send(new \Exception($e->getMessage(), $e->getCode()));
                }
            }
        }
    }


}
