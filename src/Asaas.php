<?php

namespace Hubooai\Asaas;

use Hubooai\Asaas\Http\AsaasHttpClient;
use Hubooai\Asaas\Resources\CustomerResource;
use Hubooai\Asaas\Resources\PaymentResource;
use Hubooai\Asaas\Resources\SubscriptionResource;
use Hubooai\Asaas\Resources\WebhookResource;

class Asaas
{
    protected AsaasHttpClient $client;

    public function __construct(?string $apiKey = null, ?string $environment = null)
    {
        $this->client = new AsaasHttpClient($apiKey, $environment);
    }

    /**
     * Get the HTTP client instance
     */
    public function getClient(): AsaasHttpClient
    {
        return $this->client;
    }

    /**
     * Get API information
     */
    public function info(): array
    {
        return $this->client->get('/');
    }

    /**
     * Customer management methods
     */
    public function customers(): CustomerResource
    {
        return new CustomerResource($this->client);
    }

    /**
     * Payment management methods
     */
    public function payments(): PaymentResource
    {
        return new PaymentResource($this->client);
    }

    /**
     * Subscription management methods
     */
    public function subscriptions(): SubscriptionResource
    {
        return new SubscriptionResource($this->client);
    }

    /**
     * Webhook management methods
     */
    public function webhooks(): WebhookResource
    {
        return new WebhookResource($this->client);
    }
}
