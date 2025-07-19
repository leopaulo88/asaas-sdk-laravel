<?php

namespace Leopaulo88\Asaas\Resources;

use Leopaulo88\Asaas\Entities\Account\AccountCreate;
use Leopaulo88\Asaas\Entities\Account\AccountResponse;
use Leopaulo88\Asaas\Entities\List\ListResponse;

class AccountResource extends BaseResource
{
    /**
     * List all accounts.
     *
     * Available parameters in $params:
     * - offset (int): List starting element.
     * - limit (int, ≤ 100): Number of list elements (max: 100).
     * - cpfCnpj (string): Filter by the subaccount's CPF or CNPJ.
     * - email (string): Filter by subaccount email.
     * - name (string): Filter by subaccount name.
     * - walletId (string): Filter by subaccount walletId.
     *
     * @see https://docs.asaas.com/reference/list-subaccounts
     */
    public function list(array $params = []): ListResponse
    {
        return $this->get('/accounts', $params);
    }

    /**
     * Create a new account.
     *
     * @see https://docs.asaas.com/reference/create-subaccount
     */
    public function create(array|AccountCreate $data): AccountResponse
    {
        if (is_array($data)) {
            $data = AccountCreate::fromArray($data);
        }

        return $this->post('/accounts', $data->toArray());
    }

    /**
     * Find an account by ID.
     *
     * @see https://docs.asaas.com/reference/retrieve-a-single-subaccount
     */
    public function find(string $id): AccountResponse
    {
        return $this->get("/accounts/{$id}");
    }
}
