<?php

namespace Leopaulo88\Asaas;

use Leopaulo88\Asaas\Concerns\HasAccounts;
use Leopaulo88\Asaas\Concerns\HasCreditCards;
use Leopaulo88\Asaas\Concerns\HasCustomers;
use Leopaulo88\Asaas\Concerns\HasPayments;
use Leopaulo88\Asaas\Support\AsaasClient;

class Asaas
{
    use HasAccounts, HasCreditCards, HasCustomers, HasPayments;

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
}
