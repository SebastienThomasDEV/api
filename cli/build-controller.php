<?php
$controllerTemplate = <<<PHP
<?php

namespace Api\Framework\App\Controller;


use Api\Framework\Kernel\AbstractController;
use Api\Framework\Kernel\Attributes\Endpoint;
use Api\Framework\Kernel\Http\JsonResponse;

class @^nameController extends AbstractController
{
    #[Endpoint(path: '/@name', requestMethod: 'GET')]
    public function index(): JsonResponse
    {
        \$message = "Hello World";
        return \$this->send([
            'message' => \$message
        ]);
    }
}
PHP;

echo "Veuillez entrer le nom du controller à créer\n";
$input = readline();
$input = ucfirst($input);
$controller = fopen("../src/App/Controller/{$input}Controller.php", "w");
$controllerTemplate = str_replace('@^name', $input, $controllerTemplate);
$controllerTemplate = str_replace('@name', strtolower($input), $controllerTemplate);
fwrite($controller, $controllerTemplate);
fclose($controller);
echo $input."Controller créé avec succès\n";

