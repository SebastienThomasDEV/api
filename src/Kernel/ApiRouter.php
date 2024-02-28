<?php

namespace Mvc\Framework\Kernel;

use Mvc\Framework\Kernel\Authentication\Guard;
use Mvc\Framework\Kernel\Utils\Utils;

class ApiRouter
{

    private static array $controllerEndpoints = [];
    private static array $resources = [];

    public static function searchForRoutes(): void
    {
        self::registerControllerEndpoints();
        self::registerResourceEndpoints();
        self::load();
    }


    private static function registerControllerEndpoints(): void
    {
        // j'ouvre le dossier Controller de mon application pour lire les fichiers qu'il contient
        $dir = opendir(__DIR__ . '/../../src/App/Controller');
        // je fait une boucle "tant que" pour parcourir les fichiers du dossier
        while ($file_path = readdir($dir)) {
            // je vérifie que le fichier n'est pas un dossier
            if ($file_path !== '.' && $file_path !== '..') {
                // je remplace le .php par une chaine vide pour avoir le nom de la classe
                $file_path = str_replace('.php', '', $file_path);
                // je concatène le namespace de mon controller avec le nom de la classe
                $file_path = 'Mvc\\Framework\\App\\Controller\\' . $file_path;
                try {
                    // je crée une instance de la classe ReflectionClass pour lire les attributs de ma classe
                    $class = new \ReflectionClass($file_path);
                    // je récupère les méthodes de ma classe
                    $methods = $class->getMethods();
                    // je boucle sur les méthodes de ma classe
                    foreach ($methods as $method) {
                        // je récupère les attributs de ma méthode
                        $attributes = $method->getAttributes();
                        // je récupère les paramètres de ma méthode
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
