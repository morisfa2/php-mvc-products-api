<?php

declare(strict_types=1);

namespace Tests\Http\Api\Traits;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

trait MakesHttpRequests
{
    private ?Client $client = null;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = new Client([
            'base_uri' => 'http://localhost:8000',
            'http_errors' => false
        ]);
    }

    protected function makeRequest(string $method, string $uri, array $data = []): array
    {
        try {
            if ($this->client === null) {
                $this->client = new Client([
                    'base_uri' => 'http://localhost:8000',
                    'http_errors' => false
                ]);
            }
            
            $options = ['headers' => ['Content-Type' => 'application/json']];
            
            if (!empty($data)) {
                $options['json'] = $data;
            }

            $response = $this->client->request($method, $uri, $options);
            
            return [
                'status' => $response->getStatusCode(),
                'data' => json_decode($response->getBody()->getContents(), true)
            ];
        } catch (GuzzleException $e) {
            return [
                'status' => $e->getCode(),
                'error' => $e->getMessage()
            ];
        }
    }
} 