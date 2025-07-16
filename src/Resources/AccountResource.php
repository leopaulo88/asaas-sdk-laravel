<?php

namespace Leopaulo88\Asaas\Resources;

use Leopaulo88\Asaas\Entities\Account\AccountCreate;

class AccountResource extends BaseResource
{
    /**
     * List subaccounts with optional filters
     * Returns List entity automatically
     */
    public function list(array $params = [])
    {
        return $this->get('/accounts', $params);
    }

    /**
     * Create a new subaccount
     * Returns AccountResponse entity automatically
     */
    public function create(array|AccountCreate $data)
    {
        if (is_array($data)) {
            $data = AccountCreate::fromArray($data);
        }

        return $this->post('/accounts', $data->toArray());
    }

    /**
     * Get subaccount by ID
     * Returns AccountResponse entity automatically
     */
    public function find(string $id)
    {
        return $this->get("/accounts/{$id}");
    }

    /**
     * Get the current account information
     * Returns AccountResponse entity automatically
     */
    public function me()
    {
        return $this->get('/myAccount');
    }
}
