<?php

namespace Leopaulo88\Asaas\Resources;

use Leopaulo88\Asaas\Http\AsaasClient;
use Leopaulo88\Asaas\Factories\EntityFactory;

abstract class BaseResource
{
    public function __construct(protected AsaasClient $client)
    {
    }

    protected function get(string $endpoint, array $query = [])
    {
        $response = $this->client->http()->get($endpoint, $query);
        return EntityFactory::createFromResponse($response);
    }

    protected function post(string $endpoint, array $data = [])
    {
        $response = $this->client->http()->post($endpoint, $data);
        return EntityFactory::createFromResponse($response);
    }

    protected function put(string $endpoint, array $data = [])
    {
        $response = $this->client->http()->put($endpoint, $data);
        return EntityFactory::createFromResponse($response);
    }

    protected function delete(string $endpoint)
    {
        $response = $this->client->http()->delete($endpoint);
        return EntityFactory::createFromResponse($response);
    }


    protected function getRaw(string $endpoint, array $query = [])
    {
        return $this->client->http()->get($endpoint, $query);
    }

    protected function postRaw(string $endpoint, array $data = [])
    {
        return $this->client->http()->post($endpoint, $data);
    }

    protected function putRaw(string $endpoint, array $data = [])
    {
        return $this->client->http()->put($endpoint, $data);
    }

    protected function deleteRaw(string $endpoint)
    {
        return $this->client->http()->delete($endpoint);
    }
}
