<?php

namespace Mvc\Framework\Kernel;

use Mvc\Framework\Kernel\Model\Model;

class AbstractRepository
{
    private string $entity;

    public function __construct()
    {
        $arrayDir = explode("\\", get_class($this));
        $repositoryName = end($arrayDir);
        $this->entity = strtolower(substr($repositoryName, 0, strpos($repositoryName, 'Repository')));
    }








}