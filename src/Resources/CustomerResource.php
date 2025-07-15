<?php

namespace Leopaulo88\Asaas\Resources;

use Illuminate\Http\Client\Response;
use Leopaulo88\Asaas\Entities\Customer\CustomerCreateRequest;
use Leopaulo88\Asaas\Entities\Customer\CustomerUpdateRequest;
use Leopaulo88\Asaas\Entities\Customer\CustomerResponse;

class CustomerResource extends BaseResource
{
    /**
     * List customers with optional filters
     */
    public function list(array $params = []): Response
    {
        return $this->get('/customers', $params);
    }

    /**
     * Create a new customer
     */
    public function create(array|CustomerCreateRequest $data): CustomerResponse
    {
        if (is_array($data)) {
            $data = new CustomerCreateRequest($data);
        }

        $response = $this->post('/customers', $data->toArray());
        return CustomerResponse::fromResponse($response);
    }

    /**
     * Get customer by ID
     */
    public function find(string $id): CustomerResponse
    {
        $response = $this->get("/customers/{$id}");
        return CustomerResponse::fromResponse($response);
    }

    /**
     * Update customer by ID
     */
    public function update(string $id, array|CustomerUpdateRequest $data): CustomerResponse
    {
        if (is_array($data)) {
            $data = new CustomerUpdateRequest($data);
        }

        $response = $this->put("/customers/{$id}", $data->toArray());
        return CustomerResponse::fromResponse($response);
    }

    /**
     * Delete customer by ID - returns minimal response with deleted status
     */
    public function delete(string $id): array
    {
        $response = parent::delete("/customers/{$id}");
        return $response->json() ?? [];
    }

    /**
     * Restore a deleted customer by ID
     */
    public function restore(string $id): CustomerResponse
    {
        $response = $this->post("/customers/{$id}/restore");
        return CustomerResponse::fromResponse($response);
    }
}
