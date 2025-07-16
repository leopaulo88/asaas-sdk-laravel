<?php

namespace Leopaulo88\Asaas;

use Leopaulo88\Asaas\Resources\AccountResource;
use Leopaulo88\Asaas\Resources\CustomerResource;
use Leopaulo88\Asaas\Support\AsaasClient;

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

    public function withApiKey(string $apiKey, ?string $environment = null): self
    {
        return new self($apiKey, $environment);
    }

    public function customers(): CustomerResource
    {
        return new CustomerResource($this->client);
    }

    public function accounts(): AccountResource
    {
        return new AccountResource($this->client);
    }
}
