<?php

namespace Mvc\Framework\Kernel;

use Mvc\Framework\Kernel\Model\Model;

abstract class AbstractRepository
{
    private string $entity;

    public function __construct()
    {
        $arrayDir = explode("\\", get_class($this));
        // Mvc\\Framework\\App\\Repository\\UtilisateurRepository
        $repositoryName = end($arrayDir);
        $this->entity = strtolower(substr($repositoryName, 0, strpos($repositoryName, 'Repository')));
        // utilisateur -> nom de ma table dans ma base de donnée
    }








}