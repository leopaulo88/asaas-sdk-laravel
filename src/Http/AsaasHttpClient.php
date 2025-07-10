<?php

namespace Hubooai\Asaas\Http;

use Hubooai\Asaas\Exceptions\AsaasException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;

class AsaasHttpClient
{
    protected string $apiKey;
    protected string $baseUrl;
    protected int $timeout;

    public function __construct(?string $apiKey = null, ?string $environment = null)
    {
        $this->apiKey = $apiKey ?? Config::get('asaas.api_key');
        $environment = $environment ?? Config::get('asaas.environment', 'sandbox');
        $this->baseUrl = Config::get("asaas.api_urls.{$environment}");
        $this->timeout = Config::get('asaas.timeout', 30);

        if (empty($this->apiKey)) {
            throw new AsaasException('API Key is required. Please set ASAAS_API_KEY in your environment.');
        }
    }

    protected function makeRequest(): PendingRequest
    {
        return Http::baseUrl($this->baseUrl)
            ->timeout($this->timeout)
            ->withHeaders([
                'Content-Type' => 'application/json',
                'access_token' => $this->apiKey,
                'User-Agent' => 'Asaas-SDK-Laravel/1.0.0',
            ]);
    }

    public function get(string $endpoint, array $params = []): array
    {
        $response = $this->makeRequest()->get($endpoint, $params);

        return $this->handleResponse($response);
    }

    public function post(string $endpoint, array $data = []): array
    {
        $response = $this->makeRequest()->post($endpoint, $data);

        return $this->handleResponse($response);
    }

    public function put(string $endpoint, array $data = []): array
    {
        $response = $this->makeRequest()->put($endpoint, $data);

        return $this->handleResponse($response);
    }

    public function delete(string $endpoint): array
    {
        $response = $this->makeRequest()->delete($endpoint);

        return $this->handleResponse($response);
    }

    protected function handleResponse(Response $response): array
    {
        $statusCode = $response->status();
        $body = $response->json();

        if ($statusCode >= 400) {
            $message = $body['errors'][0]['description'] ?? 'Unknown error occurred';
            throw new AsaasException($message, $statusCode);
        }

        return $body ?? [];
    }

    public function getApiKey(): string
    {
        return $this->apiKey;
    }

    public function getBaseUrl(): string
    {
        return $this->baseUrl;
    }
}
