<?php

namespace Leopaulo88\Asaas;

use Leopaulo88\Asaas\Concerns\HasAccounts;
use Leopaulo88\Asaas\Concerns\HasCreditCards;
use Leopaulo88\Asaas\Concerns\HasCustomers;
use Leopaulo88\Asaas\Concerns\HasPayments;
use Leopaulo88\Asaas\Resources\AccountResource;
use Leopaulo88\Asaas\Resources\CreditCardResource;
use Leopaulo88\Asaas\Resources\CustomerResource;
use Leopaulo88\Asaas\Resources\PaymentResource;
use Leopaulo88\Asaas\Support\AsaasClient;

class Asaas
{
    use HasAccounts, HasPayments, HasCreditCards, HasCustomers;

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
