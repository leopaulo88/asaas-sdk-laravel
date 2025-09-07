<?php

namespace Leopaulo88\Asaas;

use Leopaulo88\Asaas\Concerns\HasAccounts;
use Leopaulo88\Asaas\Concerns\HasCreditCards;
use Leopaulo88\Asaas\Concerns\HasCustomers;
use Leopaulo88\Asaas\Concerns\HasFinance;
use Leopaulo88\Asaas\Concerns\HasInstallments;
use Leopaulo88\Asaas\Concerns\HasMyAccount;
use Leopaulo88\Asaas\Concerns\HasPayments;
use Leopaulo88\Asaas\Concerns\HasPix;
use Leopaulo88\Asaas\Concerns\HasSubscriptions;
use Leopaulo88\Asaas\Concerns\HasTransfers;
use Leopaulo88\Asaas\Concerns\HasWebhooks;
use Leopaulo88\Asaas\Support\AsaasClient;

class Asaas
{
    use HasAccounts, HasCreditCards, HasCustomers, HasFinance, HasInstallments, HasMyAccount, HasPayments, HasPix, HasSubscriptions, HasTransfers, HasWebhooks;

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
