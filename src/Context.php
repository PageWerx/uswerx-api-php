<?php

namespace Pagewerx\UswerxApiPhp;

use GuzzleHttp\Client;
use Pagewerx\UswerxApiPhp\Contracts\Singleton;
use Pagewerx\UswerxApiPhp\Contracts\LoggerInterface;

class Context extends Singleton
{
    protected string|null       $token = null;
    protected string|null       $host = null;
    protected bool              $debug = false;
    protected bool              $test = false;
    protected bool              $initialized = false;
    protected Client            $httpClient;
    protected LoggerInterface   $logger;

    public function settings(
        string          $token,
        string          $host,
        LoggerInterface $logger = null,
        bool            $debug = false,
        bool            $test = false,
        Client          $httpClient = null
    ): Context
    {
        $this->setToken($token);
        $this->setHost($host);
        $this->initialized = true;
        if ($logger !== null)
        {
            $this->setLogger($logger);
        }
        $this->setDebug($debug);
        $this->setTestMode($test);
        if ($httpClient !== null)
        {
            $this->setHttpClient($httpClient);
        }
        return $this;
    }

    public function enableTestMode(): void
    {
        $this->setTestMode(true);
    }

    public function disableTestMode(): void
    {
        $this->setTestMode(false);
    }

    public function setTestMode(bool $test): void
    {
        $this->test = $test;
    }

    public function testMode(): bool
    {
        return $this->test;
    }

    public function enableDebug(): void
    {
        $this->setDebug(true);
    }

    public function disableDebug(): void
    {
        $this->setDebug(false);
    }

    private function setDebug(bool $debug): void
    {
        $this->debug = $debug;
    }

    public function debugMode(): bool
    {
        return $this->debug;
    }

    public function setLogger(LoggerInterface $logger): void
    {
        $this->logger = $logger;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function setToken(string $token): void
    {
        $this->token = $token;
    }

    public function getHost(): string
    {
        return $this->host;
    }

    public function setHost(string $host): void
    {
        $this->host = $host;
    }

    public function getClient(): Client
    {
        return $this->httpClient;
    }

    private function setHttpClient(?Client $httpClient): void
    {
        $this->httpClient = $httpClient;
    }

    public function getLogger()
    {
        return $this->logger;
    }

    public function initialized(): bool
    {
        return $this->initialized;
    }
}

