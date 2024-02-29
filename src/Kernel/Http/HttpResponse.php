<?php

namespace Mvc\Framework\Kernel\Http;

abstract class HttpResponse
{
    public function __construct(private string $content = '', private int $status = 200)
    {}

    public final function send(): void
    {
        http_response_code($this->status);
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
        header("Access-Control-Max-Age: 3600");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
        print $this->content;
    }

    public final function setContent(string $content): void
    {
        $this->content = $content;
    }

    public final function setStatusCode(int $status): void
    {
        $this->status = $status;
    }

}