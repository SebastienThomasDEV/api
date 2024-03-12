<?php

namespace Api\Framework\App\Entity;

use Api\Framework\Kernel\Attributes\ApiResource;
#[ApiResource('user')]
class User
{
    private ?int $id = null;
    private string $nom;

    private string $prenom;
    private string $email;
    private string $mdp;
    private string $roles;


    public function getNom(): string
    {
        return $this->nom;
    }

    public function getPrenom(): string
    {
        return $this->prenom;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getMdp(): string
    {
        return $this->mdp;
    }

    public function getRoles(): string
    {
        return $this->roles;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setNom(string $nom): void
    {
        $this->nom = $nom;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function setMdp(string $mdp): void
    {
        $this->mdp = $mdp;
    }

    public function setRoles(string $roles): void
    {
        $this->roles = $roles;
    }

    /**
     * @param string $prenom
     */
    public function setPrenom(string $prenom): void
    {
        $this->prenom = $prenom;
    }

}