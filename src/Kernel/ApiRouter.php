<?php

namespace Api\Framework\Kernel;

use Api\Framework\Kernel\Exception\ExceptionManager;
use Api\Framework\Kernel\Utils\Utils;


// Cette classe est un routeur qui permet de gérer les requêtes entrantes dans notre application
// Elle permet de charger les routes de notre application et de rediriger la requête de l'utilisateur vers le bon contrôleur
abstract class ApiRouter
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

        $namespace = $_ENV["NAMESPACE"];
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
                $file_path = $namespace . '\\App\\Controller\\' . $file_path;
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
                        // à l'appel de la méthode contenue dans ma classe controller
                        // je vais pouvoir lui passer des paramètres dynamiquement
                        $parameters = $method->getParameters();
                        // je boucle sur les attributs de ma méthode pour récupérer les attributs de type Endpoint
                        // car de base, je ne sais pas si ma méthode contient un attribut de type Endpoint
                        foreach ($attributes as $attribute) {
                            // je vérifie si l'attribut de ma méthode est de type Endpoint
                            if ($attribute->getName() === $namespace . '\\Kernel\\Attributes\\Endpoint') {
                                // si c'est le cas, je crée une instance de mon attribut Endpoint
                                $endpoint = $attribute->newInstance();
                                foreach (self::$controllerEndpoints as $alreadyRegisteredEndpoint) {
                                    if ($endpoint->getPath() === $alreadyRegisteredEndpoint->getPath()) {
                                        ExceptionManager::send(new \Exception('Endpoint mismatch, probably a duplicate', 500));
                                    }
                                }
                                // j'associe le chemin de mon controller à mon endpoint pour pouvoir le retrouver plus tard
                                $endpoint->setController($file_path);
                                // j'associe la méthode de mon controller à mon endpoint pour pouvoir l'appeler plus tard
                                $endpoint->setMethod($method->getName());
                                // je boucle sur les paramètres de ma méthode pour récupérer les types des paramètres
                                // car si le type du paramètre n'est pas un type primitif (int, string, bool, etc.)
                                // c'est que c'est un service ou un repository que je dois instancier pour l'injecter dans ma méthode
                                foreach ($parameters as $parameter) {
                                    // je vérifie donc si le type du paramètre n'est pas un type primitif
                                    if (!Utils::isPrimitiveFromString($parameter->getType())) {
                                        // si c'est le cas, je stocke le nom du paramètre et son type dans mon endpoint
                                        $endpoint->setParameter($parameter->getName(), $parameter->getType());
                                    } else {
                                        ExceptionManager::send(new \Exception('A non-object type parameter has been found, try to replace by an service', 500));
                                    }
                                }
                                // Pour finir, je stocke mon endpoint dans mon tableau de endpoints
                                self::$controllerEndpoints[] = $endpoint;
                            }
                        }
                    }
                    // je rattrape les exceptions qui peuvent être levées par la classe ReflectionClass
                } catch (\ReflectionException $e) {
                    // j'appelle la méthode send de la classe ExceptionManager pour envoyer une exception
                    // qui est une classe qui permet de gérer les exceptions de notre application crée dans le dossier Kernel/Exception
                    ExceptionManager::send(new \Exception($e->getMessage(), $e->getCode()));
                }
            }
        }
        // je ferme le dossier Controller de mon application
        // avec la fonction closedir de PHP qui permet de fermer un dossier
        // cela permet de libérer les ressources utilisées par le dossier et éviter des fuites de mémoire
        closedir($dir);
    }


    // on déclare une méthode statique et publique pour enregistrer les resources de nos entités
    // dans cette méthode, on va parcourir le dossier Entity de notre application pour lire les fichiers qu'il contient
    // car chaque fichier correspond à une entité si on respecte la convention de nommage (NomDeL'Entité.php)
    // grace à la classe ReflectionClass, on va lire les metadonnées de nos classes pour récupérer les attributs de type ApiResource
    // si on trouve un attribut de type ApiResource
    // on va appeler la méthode buildEndpoints de notre resource pour générer les routes de l'API associées à notre entité
    // exemple: si on a une entité Utilisateur, on va générer les routes de l'API pour cette entité (GET /utilisateurs, POST /utilisateurs, etc.)
    public static function registerResourceEndpoints(): void
    {
        $namespace = $_ENV["NAMESPACE"];
        // j'ouvre le dossier Entity de mon application pour lire les fichiers qu'il contient (les entités)
        $dir = opendir(__DIR__ . '/../../src/App/Entity');
        // je fait une boucle "tant que" pour parcourir les fichiers du dossier Entity
        while ($file_path = readdir($dir)) {
            // Encore une fois, je vérifie si le fichier n'est pas un dossier (.) ou (..) car readdir lit aussi les dossiers
            if ($file_path !== '.' && $file_path !== '..') {
                // je remplace l'extension .php de mon fichier par une chaine vide
                $className = str_replace('.php', '', $file_path);
                // je concatène le namespace de mon entity avec le nom de la classe
                $file_path = $namespace . '\\App\\Entity\\' . $className;
                try {
                    // en utilisant le namespace complet de ma classe, je crée une instance de la classe ReflectionClass
                    $class = new \ReflectionClass($file_path);
                    // je récupère les attributs de ma classe
                    $attributes = $class->getAttributes();
                    // je boucle sur les attributs de ma classe pour récupérer les attributs de type ApiResource
                    foreach ($attributes as $attribute) {
                        // je vérifie si l'attribut de ma classe est de type ApiResource
                        if ($attribute->getName() === $namespace . '\\Kernel\\Attributes\\ApiResource') {
                            // si c'est le cas, je crée une instance de mon attribut ApiResource
                            // dans son constructeur, je vais appeler la méthode buildEndpoints pour générer les routes de l'API associées à mon entité
                            $resource = $attribute->newInstance();
                            // je stocke les routes de l'API associées à mon entité dans mon tableau de resources qui me servira plus tard
                            // je bouclerais sur ce tableau pour rediriger la requête de l'utilisateur vers l'endpoint correspondant
                            self::$resources[$className] = $resource->getResourceEndpoints();
                        }
                    }
                } catch (\ReflectionException $e) {
                    ExceptionManager::send(new \Exception($e->getMessage(), $e->getCode()));
                }
            }
        }
        closedir($dir);
    }

    // on déclare une méthode statique et publique pour charger l'endpoint correspondant à la requête de l'utilisateur
    // dans cette méthode, on va parcourir notre tableau de endpoints pour trouver l'endpoint correspondant à la requête de l'utilisateur
    // si on trouve l'endpoint correspondant, on va instancier le contrôleur associé et appeler la méthode associée
    // si on ne trouve pas l'endpoint correspondant, on envoie une exception pour signaler que l'endpoint n'a pas été trouvé
    public static function loadEndpoint(): void
    {
        // je déclare une variable pour stocker l'endpoint correspondant à la requête de l'utilisateur
        // elle est initialisée à null car je ne sais pas si je vais trouver l'endpoint correspondant
        $endpointFound = null;
        // je boucle sur mon tableau de endpoints pour trouver l'endpoint correspondant à la requête de l'utilisateur
        foreach (self::$controllerEndpoints as $endpoint) {
            // je vérifie si le chemin de mon endpoint correspond à la requête de l'utilisateur
            // exemple: si l'utilisateur demande /utilisateurs, je vérifie si mon endpoint correspond à /utilisateurs
            if ($endpoint->getPath() === Utils::getUrn()) {
                // je vérifie si la méthode de mon endpoint correspond à la méthode de la requête de l'utilisateur
                if ($endpoint->getRequestMethod() === Utils::getRequestedMethod()) {
                    // je vérifie si mon endpoint est protégé par un token
                    $endpointFound = $endpoint;
                } else {
                    // si la méthode de la requête de l'utilisateur ne correspond pas à la méthode de mon endpoint
                    // exemple: si l'utilisateur demande /utilisateurs avec la méthode POST, mais mon endpoint est en GET
                    // j'envoie une exception pour signaler que la méthode n'est pas autorisée
                    ExceptionManager::send(new \Exception('Method not allowed for this endpoint', 405));
                }
            }
        }
        if (!$endpointFound) {
            foreach (self::$resources as $resource) {
                if ($identifier = Utils::getRequestIdentifier()) {
                    if ($resource[Utils::getRequestedMethod()] && is_numeric($identifier)) {
                        if (Utils::getUrn() === $resource[Utils::getRequestedMethod()]->getPath().'/'.$identifier) {
                            $resource[Utils::getRequestedMethod()]->execute((int)$identifier);
                        }
                    }
                } else {
                    if ($resource[Utils::getRequestedMethod()]) {
                        if (Utils::getUrn() === $resource[Utils::getRequestedMethod()]->getPath()) {
                            $resource[Utils::getRequestedMethod()]->execute();
                        }
                    }
                }
            }
            ExceptionManager::send(new \Exception('API endpoint not found', 404));
        } else {
            // si je trouve l'endpoint correspondant à la requête de l'utilisateur
            if (class_exists($endpointFound->getController())) {
                // je vérifie si le contrôleur associé à mon endpoint existe
                // si c'est le cas, j'instancie mon contrôleur et j'appelle la méthode associée
                $controller = new \ReflectionClass($endpointFound->getController());
                try {
                    // je crée une instance de mon contrôleur
                    $controller = $controller->newInstance();
                    // je vérifie si la méthode associée à mon endpoint existe
                    // si c'est le cas, j'appelle la méthode associée à mon contrôleur
                    if (method_exists($controller, $endpointFound->getMethod())) {
                        // je stocke la méthode associée à mon endpoint dans une variable
                        $method = $endpointFound->getMethod();
                        // j'utilise ma classe DependencyResolver pour instancier les services associés à ma méthode
                        // cette classe utilise le système de réflexion de PHP pour instancier les services associés à ma méthode
                        // et les injecter dans les paramètres de ma méthode (Voir DependencyResolver.php)
                        $services = DependencyResolver::resolve($endpointFound->getParameters());
                        // j'appelle la méthode associée à mon endpoint avec les services associés
                        // l'opérateur de décomposition (...) permet de passer un tableau de paramètres à une méthode
                        // cela permet de passer des paramètres dynamiquement à une méthode
                        // exemple: $controller->$method($service1, $service2, $service3, etc.)
                        // car je ne sais pas combien de services je vais instancier pour ma méthode de mon contrôleur
                        // cela me permet de passer les services associés à ma méthode dynamiquement
                        // et pouvoir les utiliser dans ma méthode
                        $controller->$method(...$services);
                    }
                } catch (\ReflectionException $e) {
                    ExceptionManager::send(new \Exception($e->getMessage(), $e->getCode()));
                }
            }
        }
    }

}
