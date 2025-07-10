<?php

namespace Hubooai\Asaas;

use Hubooai\Asaas\Http\AsaasHttpClient;
use Hubooai\Asaas\Resources\AccountResource;
use Hubooai\Asaas\Resources\CustomerResource;
use Hubooai\Asaas\Resources\PaymentResource;
use Hubooai\Asaas\Resources\SubscriptionResource;
use Hubooai\Asaas\Resources\WebhookResource;

class Asaas
{
    protected AsaasHttpClient $client;
    protected array $resources = [];

    public function __construct(?string $apiKey = null, ?string $environment = null)
    {
        $this->client = new AsaasHttpClient($apiKey, $environment);
    }

    /**
     * Create a new instance with a different API key
     */
    public function withApiKey(string $apiKey): self
    {
        return new self($apiKey, $this->client->getEnvironment());
    }

    /**
     * Get the HTTP client instance
     */
    public function getClient(): AsaasHttpClient
    {
        return $this->client;
    }

    /**
     * Get accounts resource
     */
    public function accounts(): AccountResource
    {
        if (!isset($this->resources['accounts'])) {
            $this->resources['accounts'] = new AccountResource($this->client);
        }

        return $this->resources['accounts'];
    }

    /**
     * Get customers resource
     */
    public function customers(): CustomerResource
    {
        if (!isset($this->resources['customers'])) {
            $this->resources['customers'] = new CustomerResource($this->client);
        }

        return $this->resources['customers'];
    }

    /**
     * Get payments resource
     */
    public function payments(): PaymentResource
    {
        if (!isset($this->resources['payments'])) {
            $this->resources['payments'] = new PaymentResource($this->client);
        }

        return $this->resources['payments'];
    }

    /**
     * Get subscriptions resource
     */
    public function subscriptions(): SubscriptionResource
    {
        if (!isset($this->resources['subscriptions'])) {
            $this->resources['subscriptions'] = new SubscriptionResource($this->client);
        }

        return $this->resources['subscriptions'];
    }

    /**
     * Get webhooks resource
     */
    public function webhooks(): WebhookResource
    {
        if (!isset($this->resources['webhooks'])) {
            $this->resources['webhooks'] = new WebhookResource($this->client);
        }

        return $this->resources['webhooks'];
    }

    /**
     * Get API information
     */
    public function info(): array
    {
        return $this->client->get('/');
    }

}
