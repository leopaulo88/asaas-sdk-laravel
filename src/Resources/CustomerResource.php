<?php

namespace Leopaulo88\Asaas\Resources;

use Leopaulo88\Asaas\Entities\Customer\CustomerCreateEntity;
use Leopaulo88\Asaas\Entities\Customer\CustomerUpdateEntity;

class CustomerResource extends BaseResource
{

    public function list(array $params = [])
    {
        return $this->get('/customers', $params);
    }


    public function create(array|CustomerCreateEntity $data)
    {
        if (is_array($data)) {
            $data = CustomerCreateEntity::fromArray($data);
        }

        return $this->post('/customers', $data->toArray());
    }


    public function find(string $id)
    {
        return $this->get("/customers/{$id}");
    }


    public function update(string $id, array|CustomerUpdateEntity $data)
    {
        if (is_array($data)) {
            $data = CustomerUpdateEntity::fromArray($data);
        }

        return $this->put("/customers/{$id}", $data->toArray());
    }


    public function delete(string $id)
    {
        return parent::delete("/customers/{$id}");
    }


    public function restore(string $id)
    {
        return $this->post("/customers/{$id}/restore");
    }
}
