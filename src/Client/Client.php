<?php

namespace Pagewerx\UswerxApiPhp\Client;

use GuzzleHttp\Exception\GuzzleException;
use Pagewerx\UswerxApiPhp\Context;
use Pagewerx\UswerxApiPhp\Contracts\LoggerInterface;
use Pagewerx\UswerxApiPhp\DraftOrder\draft;

class Client
{
    private string $token;
    private string $host;
    private bool $debug = false;
    private bool $test = false;
    private \GuzzleHttp\Client $httpClient;
    public LoggerInterface $logger;


    public function __construct()
    {
        $context = Context::getInstance();
        if (!$context->initialized()) {
            throw new \Exception('The context Singleton has not been initialized.');
        }

        $this->token = $context->getToken();
        $this->host = $context->getHost();
        $this->debug = $context->debugMode();
        $this->test = $context->testMode();
        if ($this->test) {
            $this->host = 'https://example.com';
            $this->httpClient = $context->getClient();
        }
    }

    public function enableDebug(): Client
    {
        $this->debug = true;
        return $this;
    }

    public function disableDebug(): Client
    {
        $this->debug = false;
        return $this;
    }

    public function debugEnabled()
    {
        return $this->debug;
    }

    public function createDraftOrder($data = []): draft
    {
        try {
            $this->debugLog('Creating Draft Order', $data);
            $endpoint = $this->host . '/api/draft-orders';
            // Initialize Guzzle
            $client = new \GuzzleHttp\Client();
            // set headers
            $headers = [
                'Authorization' => 'Bearer ' . $this->token,
                'Content-Type' => 'application/x-www-form-urlencoded',
            ];
            // set form data field line_items to the comma delimited list of SKUs
            if (!empty($data['line_items'])) {
                if (is_array($data['line_items'])) {
                    $form_data['line_items'] = implode(',', $data['line_items']);
                } else {
                    $form_data['line_items'] = $data['line_items'];
                }
            }

            //post $form_data to the endpoint
            $response = $this->httpClient->post($endpoint, [
                'headers' => $headers,
                'form_params' => $form_data
            ]);
            $draftObj = json_decode($response->getBody()->getContents(), false);
            $draft = new draft($draftObj->id,$draftObj);
            return $draft;
        } catch (\Exception $e) {
            $this->debugLog('Exception: '.$e->getMessage());
            throw new \Exception('An error occurred while processing API request: '.$e->getMessage(), $e->getCode());
        } catch (GuzzleException $e) {
            $this->debugLog('GuzzleException: '.$e->getMessage());
            throw new \Exception('An error occurred while processing API request: '.$e->getMessage(), $e->getCode());
        }
    }

    private function getLogger(): ?LoggerInterface
    {
        return $this->logger;
    }
    private function loggingEnabled(): bool
    {
        return !empty($this->getLogger());
    }

    private function debugLogEnabled(): bool
    {
        return $this->debug && $this->loggingEnabled();
    }

    private function debugLog($message, $context = []): void
    {
        if ($this->debugLogEnabled()) {
            $this->logger->debug($message, $context);
        }
    }

    public function getHost()
    {
        return $this->host;
    }

    public function getToken()
    {
        return $this->token;
    }

    public function post(string $endpoint, array $array)
    {
        $this->debugLog('Posting to '.$endpoint, $array);
        $response = $this->httpClient->post($endpoint, $array);
        return $response;
    }
}