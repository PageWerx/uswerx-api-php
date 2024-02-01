<?php

namespace Pagewerx\Uswerx;

class Client
{
    private string $token;

    public function __construct(string $token)
    {
        // Make sure Guzzle is present
        if (!class_exists('GuzzleHttp\Client')) {
            throw new \Exception('GuzzleHttp\Client not found. Please run composer require guzzlehttp/guzzle or include it in your class autoloader.');
        }

        $this->token = $token;
    }
}