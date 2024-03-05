<?php

namespace Mvc\Framework\Kernel;

use Mvc\Framework\Kernel\Model\Model;


// Cette classe est une classe abstraite qui permet de factoriser le code des classes Repository de l'application.
// Elle permet de définir le nom de l'entité associée à la classe Repository qui l'étend.
// Elle permet aussi de définir des méthodes génériques pour les classes Repository.
// dans son constructeur, elle récupère le nom de la classe Repository qui l'étend
// elle extrait le nom de l'entité associée à la classe Repository
// elle stocke ce nom dans une propriété privée
abstract class AbstractRepository
{

    // on déclare une propriété privée pour stocker le nom de l'entité associée à la classe Repository
    private string $entity;

    // Dans son constructeur, elle récupère le nom de la classe Repository qui l'étend
    // on utilise la fonction get_class pour récupérer le nom de la classe Repository
    // celle ci affiche le nom complet de la classe enfant qui l'étend et non pas le nom de la classe AbstractRepository
    // car get_class retourne le nom complet de la classe de l'objet qui l'appelle
    public function __construct()
    {
        // on récupère le nom complet de la classe Repository qui l'étend
        // exemple : App\Repositories\UserRepository
        $arrayDir = explode("\\", get_class($this));
        // on extrait le nom de la classe Repository
        // exemple : UserRepository
        $repositoryName = end($arrayDir);
        // on extrait le nom de l'entité associée à la classe Repository
        // exemple : user
        $this->entity = strtolower(substr($repositoryName, 0, strpos($repositoryName, 'Repository')));
    }








}