<?php

namespace Hubooai\Asaas\Resources;

class WebhookResource extends BaseResource
{
    protected string $endpoint = '/webhooks';

    /**
     * List webhooks with optional filters
     */
    public function list(array $filters = []): array
    {
        return parent::list($filters);
    }

    /**
     * Create a new webhook
     */
    public function create(array $data): array
    {
        return parent::create($data);
    }

    /**
     * Update webhook information
     */
    public function update(string $id, array $data): array
    {
        return parent::update($id, $data);
    }

    /**
     * Get webhook by ID
     */
    public function get(string $id): array
    {
        return parent::get($id);
    }

    /**
     * Delete a webhook
     */
    public function delete(string $id): array
    {
        return parent::delete($id);
    }

    /**
     * Test webhook connection
     */
    public function test(string $id): array
    {
        return $this->client->post("{$this->endpoint}/{$id}/test");
    }

    /**
     * Get webhook events
     */
    public function getEvents(): array
    {
        return $this->client->get('/webhooks/events');
    }
}
