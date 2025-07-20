<?php

namespace Leopaulo88\Asaas\Resources;

use Leopaulo88\Asaas\Entities\Common\Deleted;
use Leopaulo88\Asaas\Entities\Customer\CustomerCreate;
use Leopaulo88\Asaas\Entities\Customer\CustomerResponse;
use Leopaulo88\Asaas\Entities\Customer\CustomerUpdate;
use Leopaulo88\Asaas\Entities\List\ListResponse;

class CustomerResource extends BaseResource
{
    /**
     * List of customers.
     * Retrieves a list of customers based on the provided parameters.
     *
     * Available parameters in `$params`:
     * - `offset` (int): Initial element of the list.
     * - `limit` (int, ≤ 100): Number of elements in the list (max: 100).
     * - `name` (string): Filter by name.
     * - `email` (string): Filter by email.
     * - `cpfCnpj` (string): Filter by CPF or CNPJ.
     * - `groupName` (string): Filter by group.
     * - `externalReference` (string): Filter by your system identifier.
     *
     * @see https://docs.asaas.com/reference/list-customers
     */
    public function list(array $params = []): ListResponse
    {
        return $this->get('/customers', $params);
    }

    /**
     * Create a new customer.
     *
     * @see https://docs.asaas.com/reference/create-new-customer
     */
    public function create(array|CustomerCreate $data): CustomerResponse
    {
        if (is_array($data)) {
            $data = CustomerCreate::fromArray($data);
        }

        return $this->post('/customers', $data->toArray());
    }

    /**
     * Find a customer by ID.
     *
     * @see https://docs.asaas.com/reference/retrieve-a-single-customer
     */
    public function find(string $id): CustomerResponse
    {
        return $this->get("/customers/{$id}");
    }

    /**
     * Update an existing customer.
     *
     * @see https://docs.asaas.com/reference/update-existing-customer
     */
    public function update(string $id, array|CustomerUpdate $data): CustomerResponse
    {
        if (is_array($data)) {
            $data = CustomerUpdate::fromArray($data);
        }

        return $this->put("/customers/{$id}", $data->toArray());
    }

    /**
     * Delete a customer.
     *
     * @see https://docs.asaas.com/reference/remove-customer
     */
    public function delete(string $id): Deleted
    {
        $response = parent::delete("/customers/{$id}");

        return Deleted::fromArray($response);
    }

    /**
     * Restore a deleted customer.
     *
     * @see https://docs.asaas.com/reference/restore-removed-customer
     */
    public function restore(string $id): CustomerResponse
    {
        return $this->post("/customers/{$id}/restore");
    }
}
