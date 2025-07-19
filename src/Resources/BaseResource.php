<?php

namespace Leopaulo88\Asaas\Resources;

use Illuminate\Http\Client\RequestException;
use Leopaulo88\Asaas\Exceptions\AsaasException;
use Leopaulo88\Asaas\Exceptions\BadRequestException;
use Leopaulo88\Asaas\Exceptions\NotFoundException;
use Leopaulo88\Asaas\Exceptions\UnauthorizedException;
use Leopaulo88\Asaas\Support\AsaasClient;
use Leopaulo88\Asaas\Support\EntityFactory;

abstract class BaseResource
{
    public function __construct(protected AsaasClient $client) {}

    protected function get(string $endpoint, array $query = [])
    {
        return $this->handleRequest(fn () => $this->getRaw($endpoint, $query));
    }

    protected function post(string $endpoint, array $data = [])
    {
        return $this->handleRequest(fn () => $this->postRaw($endpoint, $data));
    }

    protected function put(string $endpoint, array $data = [])
    {
        return $this->handleRequest(fn () => $this->putRaw($endpoint, $data));
    }

    protected function delete(string $endpoint)
    {
        return $this->handleRequest(fn () => $this->deleteRaw($endpoint));
    }

    private function handleRequest(callable $httpCall)
    {
        try {
            $response = $httpCall();
            $response->throw();

            return EntityFactory::createFromResponse($response);
        } catch (RequestException $e) {
            $response = $e->response;
            $body = $response?->json() ?? [];
            $message = data_get($body, 'errors.0.description', $e->getMessage());
            $status = $response?->status() ?? 0;
            switch ($status) {
                case 400:
                    throw new BadRequestException($message, $status, $e, $body);
                case 401:
                    throw new UnauthorizedException($message, $status, $e, $body);
                case 404:
                    throw new NotFoundException($message, $status, $e, $body);
                default:
                    throw new AsaasException($message, $status, $e, $body);
            }
        }
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
