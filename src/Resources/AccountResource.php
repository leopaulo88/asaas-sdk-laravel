<?php

namespace Hubooai\Asaas\Resources;

use Hubooai\Asaas\Entities\Account\AccountCreateRequest;
use Hubooai\Asaas\Entities\Account\AccountResponse;
use Hubooai\Asaas\Http\AsaasHttpClient;

class AccountResource extends BaseResource
{
    protected string $endpoint = 'accounts';

    public function __construct(AsaasHttpClient $client)
    {
        parent::__construct($client);
    }

    /**
     * Create a new sub-account and return typed response
     */
    public function createAccount(array $data): AccountResponse
    {
        $response = $this->create($data);
        return AccountResponse::fromArray($response);
    }

    /**
     * Get account by ID and return typed response
     */
    public function getAccount(string $id): AccountResponse
    {
        $response = $this->get($id);
        return AccountResponse::fromArray($response);
    }

    /**
     * Update account information and return typed response
     */
    public function updateAccount(string $id, array $data): AccountResponse
    {
        $response = $this->update($id, $data);
        return AccountResponse::fromArray($response);
    }

    /**
     * List all accounts with typed responses
     */
    public function listAccounts(array $filters = []): array
    {
        $response = $this->list($filters);

        if (isset($response['data'])) {
            $response['data'] = array_map(
                fn($account) => AccountResponse::fromArray($account),
                $response['data']
            );
        }

        return $response;
    }
}
