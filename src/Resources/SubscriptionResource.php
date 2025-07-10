<?php

namespace Hubooai\Asaas\Resources;

class SubscriptionResource extends BaseResource
{
    protected string $endpoint = '/subscriptions';

    /**
     * List subscriptions with optional filters
     */
    public function list(array $filters = []): array
    {
        return parent::list($filters);
    }

    /**
     * Create a new subscription
     */
    public function create(array $data): array
    {
        return parent::create($data);
    }

    /**
     * Update subscription information
     */
    public function update(string $id, array $data): array
    {
        return parent::update($id, $data);
    }

    /**
     * Get subscription by ID
     */
    public function get(string $id): array
    {
        return parent::get($id);
    }

    /**
     * Delete a subscription
     */
    public function delete(string $id): array
    {
        return parent::delete($id);
    }

    /**
     * Cancel a subscription
     */
    public function cancel(string $id): array
    {
        return $this->client->post("{$this->endpoint}/{$id}/cancel");
    }

    /**
     * Reactivate a subscription
     */
    public function reactivate(string $id): array
    {
        return $this->client->post("{$this->endpoint}/{$id}/reactivate");
    }

    /**
     * Get subscription payments
     */
    public function getPayments(string $id, array $filters = []): array
    {
        return $this->client->get("{$this->endpoint}/{$id}/payments", $filters);
    }

    /**
     * Get subscription invoices
     */
    public function getInvoices(string $id, array $filters = []): array
    {
        return $this->client->get("{$this->endpoint}/{$id}/invoices", $filters);
    }
}
