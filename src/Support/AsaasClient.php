<?php

namespace Leopaulo88\Asaas\Support;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Leopaulo88\Asaas\Exceptions\InvalidApiKeyException;
use Leopaulo88\Asaas\Exceptions\InvalidEnvironmentException;

class AsaasClient
{
    protected array $config;

    public function __construct(?string $apiKey = null, ?string $environment = null)
    {
        $this->config = $this->buildConfig($apiKey, $environment);
        $this->validateConfig();
    }

    public function http(): PendingRequest
    {
        return Http::baseUrl($this->config['base_url'])
            ->timeout($this->config['timeout'])
            ->withHeaders($this->getHeaders())
            ->when($this->isRateLimitEnabled(), function (PendingRequest $request) {
                return $request->withOptions([
                    'rate_limit' => $this->config['rate_limit'],
                ]);
            });
    }

    protected function buildConfig(?string $apiKey, ?string $environment): array
    {
        $environment = $environment ?? Config::get('asaas.environment', 'sandbox');
        $apiUrls = Config::get('asaas.api_urls', []);

        return [
            'api_key' => $apiKey ?? Config::get('asaas.api_key'),
            'environment' => $environment,
            'base_url' => $apiUrls[$environment] ?? '',
            'timeout' => Config::get('asaas.timeout', 30),
            'rate_limit' => Config::get('asaas.rate_limit', []),
        ];
    }

    protected function validateConfig(): void
    {
        if (! $this->config['api_key']) {
            throw new InvalidApiKeyException;
        }

        $availableEnvironments = array_keys(config('asaas.api_urls', []));
        if (! in_array($this->config['environment'], $availableEnvironments, true)) {
            throw new InvalidEnvironmentException($this->config['environment'], $availableEnvironments);
        }

        if (! $this->config['base_url']) {
            throw new InvalidEnvironmentException($this->config['environment'], $availableEnvironments);
        }
    }

    /**
     * @return array<string, mixed>
     */
    protected function getHeaders(): array
    {
        return [
            'access_token' => $this->config['api_key'],
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'User-Agent' => 'Asaas-SDK-Laravel/1.0',
        ];
    }

    protected function isRateLimitEnabled(): bool
    {
        return (bool) ($this->config['rate_limit']['enabled'] ?? false);
    }
}
