<?php

namespace Hubooai\Asaas\Resources;

use Hubooai\Asaas\Http\AsaasHttpClient;

abstract class BaseResource
{
    protected AsaasHttpClient $client;
    protected string $endpoint;

    public function __construct(AsaasHttpClient $client)
    {
        $this->client = $client;
    }

    /**
     * List all resources with optional filters
     */
    public function list(array $filters = []): array
    {
        return $this->client->get($this->endpoint, $filters);
    }

    /**
     * Get a specific resource by ID
     */
    public function get(string $id): array
    {
        return $this->client->get("{$this->endpoint}/{$id}");
    }

    /**
     * Create a new resource
     */
    public function create(array $data): array
    {
        return $this->client->post($this->endpoint, $data);
    }

    /**
     * Update an existing resource
     */
    public function update(string $id, array $data): array
    {
        return $this->client->put("{$this->endpoint}/{$id}", $data);
    }

    /**
     * Delete a resource
     */
    public function delete(string $id): array
    {
        return $this->client->delete("{$this->endpoint}/{$id}");
    }
}
