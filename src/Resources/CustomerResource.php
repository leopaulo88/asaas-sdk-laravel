<?php

namespace Hubooai\Asaas\Resources;

class CustomerResource extends BaseResource
{
    protected string $endpoint = '/customers';

    /**
     * List customers with optional filters
     */
    public function list(array $filters = []): array
    {
        return parent::list($filters);
    }

    /**
     * Create a new customer
     */
    public function create(array $data): array
    {
        return parent::create($data);
    }

    /**
     * Update customer information
     */
    public function update(string $id, array $data): array
    {
        return parent::update($id, $data);
    }

    /**
     * Get customer by ID
     */
    public function get(string $id): array
    {
        return parent::get($id);
    }

    /**
     * Delete a customer
     */
    public function delete(string $id): array
    {
        return parent::delete($id);
    }

    /**
     * Restore a deleted customer
     */
    public function restore(string $id): array
    {
        return $this->client->post("{$this->endpoint}/{$id}/restore");
    }

    /**
     * Get customer notifications
     */
    public function getNotifications(string $id): array
    {
        return $this->client->get("{$this->endpoint}/{$id}/notifications");
    }

    /**
     * Update customer notifications settings
     */
    public function updateNotifications(string $id, array $data): array
    {
        return $this->client->put("{$this->endpoint}/{$id}/notifications", $data);
    }
}
