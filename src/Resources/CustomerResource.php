<?php

namespace Leopaulo88\Asaas\Resources;

use Leopaulo88\Asaas\Entities\Customer\CustomerCreateRequest;
use Leopaulo88\Asaas\Entities\Customer\CustomerUpdateRequest;

class CustomerResource extends BaseResource
{
    /**
     * List customers with optional filters
     * Returns List entity automatically
     */
    public function list(array $params = [])
    {
        return $this->get('/customers', $params);
    }

    /**
     * Create a new customer
     * Returns CustomerResponse entity automatically
     */
    public function create(array|CustomerCreateRequest $data)
    {
        if (is_array($data)) {
            $data = new CustomerCreateRequest($data);
        }

        return $this->post('/customers', $data->toArray());
    }

    /**
     * Get customer by ID
     * Returns CustomerResponse entity automatically
     */
    public function find(string $id)
    {
        return $this->get("/customers/{$id}");
    }

    /**
     * Update customer by ID
     * Returns CustomerResponse entity automatically
     */
    public function update(string $id, array|CustomerUpdateRequest $data)
    {
        if (is_array($data)) {
            $data = new CustomerUpdateRequest($data);
        }

        return $this->put("/customers/{$id}", $data->toArray());
    }

    /**
     * Delete customer by ID
     * Returns whatever the API returns (usually success confirmation)
     */
    public function delete(string $id)
    {
        return parent::delete("/customers/{$id}");
    }

    /**
     * Restore a deleted customer by ID
     * Returns CustomerResponse entity automatically
     */
    public function restore(string $id)
    {
        return $this->post("/customers/{$id}/restore");
    }
}
