<?php

namespace Api\Framework\Kernel\Attributes;


#[\Attribute(\Attribute::TARGET_METHOD | \Attribute::TARGET_CLASS)]
class Guarded
{

    public function __construct(
        private Endpoint $endpoint
    ){}

    /**
     * @param Endpoint $endpoint
     */
    public final function setEndpoint(Endpoint $endpoint): void
    {
        $this->endpoint = $endpoint;
    }


}