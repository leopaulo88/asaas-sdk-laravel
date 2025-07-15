<?php

namespace Leopaulo88\AsaasSdkLaravel\Resources;

use Leopaulo88\AsaasSdkLaravel\Http\AsaasClient;

abstract class BaseResource
{
    public function __construct(protected AsaasClient $client)
    {
    }

    protected function get(string $endpoint, array $query = [])
    {
        return $this->client->http()->get($endpoint, $query);
    }

    protected function post(string $endpoint, array $data = [])
    {
        return $this->client->http()->post($endpoint, $data);
    }

    protected function put(string $endpoint, array $data = [])
    {
        return $this->client->http()->put($endpoint, $data);
    }

    protected function delete(string $endpoint)
    {
        return $this->client->http()->delete($endpoint);
    }
}
