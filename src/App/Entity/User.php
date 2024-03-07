<?php

namespace Mvc\Framework\App\Entity;



use Mvc\Framework\Kernel\Attributes\ApiResource;

#[ApiResource('User')]
class User
{
    private int $id;


    /**
     * Get the value of id
     */ 
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */ 
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }
}