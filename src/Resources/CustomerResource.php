<?php

namespace Leopaulo88\AsaasSdkLaravel\Resources;

use Illuminate\Http\Client\Response;
use Leopaulo88\AsaasSdkLaravel\Entities\Customer\CustomerCreateRequest;
use Leopaulo88\AsaasSdkLaravel\Entities\Customer\CustomerUpdateRequest;
use Leopaulo88\AsaasSdkLaravel\Entities\Customer\CustomerResponse;

class CustomerResource extends BaseResource
{
    public function list(array $params = []): Response
    {
        return $this->get('/customers', $params);
    }

    public function create(array|CustomerCreateRequest $data): CustomerResponse
    {
        if (is_array($data)) {
            $data = new CustomerCreateRequest($data);
        }

        $response = $this->post('/customers', $data->toArray());
        return CustomerResponse::fromResponse($response);
    }

    public function find(string $id): CustomerResponse
    {
        $response = $this->get("/customers/{$id}");
        return CustomerResponse::fromResponse($response);
    }

    public function update(string $id, array|CustomerUpdateRequest $data): CustomerResponse
    {
        if (is_array($data)) {
            $data = new CustomerUpdateRequest($data);
        }

        $response = $this->put("/customers/{$id}", $data->toArray());
        return CustomerResponse::fromResponse($response);
    }

    public function delete(string $id): bool
    {
        $response = $this->delete("/customers/{$id}");
        return $response->successful();
    }
}
