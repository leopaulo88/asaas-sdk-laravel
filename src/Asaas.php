<?php

namespace Leopaulo88\AsaasSdkLaravel;

use Leopaulo88\AsaasSdkLaravel\Http\AsaasClient;
use Leopaulo88\AsaasSdkLaravel\Resources\CustomerResource;

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

    public function customers(): CustomerResource
    {
        return new CustomerResource($this->client);
    }

    // Futuros resources seguirão o mesmo padrão:
    // public function payments(): PaymentResource
    // public function subscriptions(): SubscriptionResource
    // public function webhooks(): WebhookResource
}
