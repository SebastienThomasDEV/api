<?php

namespace Mvc\Framework\Kernel\Services;

use Mvc\Framework\Kernel\Utils\Utils;

class Request
{

    private array $get;
    private array $post;
    private array $identifier;


    public function __construct()
    {
        $this->get = $_GET;
        $this->post = Utils::getRequestBody();
    }

    /**
     * @return array
     */
    public function getGetValues(): array
    {
        return $this->get;
    }

    /**
     * @return array
     */
    public function getPostValues(): array
    {
        return $this->post;
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function getGetValue(string $key): mixed
    {
        return $this->get[$key];
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function getPostValue(string $key): mixed
    {
        return $this->post[$key];
    }


}