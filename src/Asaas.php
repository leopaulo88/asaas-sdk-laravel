<?php

namespace Leopaulo88\Asaas;

use Leopaulo88\Asaas\Http\AsaasClient;
use Leopaulo88\Asaas\Resources\CustomerResource;

class Asaas
{
    protected AsaasClient $client;

    public function __construct(?string $apiKey = null, ?string $environment = null)
    {
        $this->client = new AsaasClient($apiKey, $environment);
    }

    public function client(): AsaasClient
    {
        return $this->client;
    }

    public function withApiKey(string $apiKey): self
    {
        return new self($apiKey, $this->client->getEnvironment());
    }

    public function customers(): CustomerResource
    {
        return new CustomerResource($this->client);
    }
}
