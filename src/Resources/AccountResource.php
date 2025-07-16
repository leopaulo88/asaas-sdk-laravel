<?php

namespace Leopaulo88\Asaas\Resources;

use Leopaulo88\Asaas\Entities\Account\AccountCreate;

class AccountResource extends BaseResource
{
    public function list(array $params = [])
    {
        return $this->get('/accounts', $params);
    }

    public function create(array|AccountCreate $data)
    {
        if (is_array($data)) {
            $data = AccountCreate::fromArray($data);
        }

        return $this->post('/accounts', $data->toArray());
    }

    public function find(string $id)
    {
        return $this->get("/accounts/{$id}");
    }

    public function me()
    {
        return $this->get('/myAccount');
    }
}
