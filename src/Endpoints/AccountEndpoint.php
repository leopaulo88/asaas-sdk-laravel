<?php

namespace Hubooai\Asaas\Endpoints;

use Hubooai\Asaas\Entities\Account\AccountCreateRequest;
use Hubooai\Asaas\Entities\Account\AccountResponse;
use Hubooai\Asaas\Resources\AccountResource;

class AccountEndpoint
{
    protected AccountResource $resource;

    public function __construct(AccountResource $resource)
    {
        $this->resource = $resource;
    }

    /**
     * Create a new sub-account
     *
     * Usage examples:
     * - create(closure) - Uses builder pattern
     * - create(name, email, cpfCnpj) - Simple creation
     * - create(AccountCreateRequest) - Direct DTO
     * - create(array) - From array
     */
    public function create($nameOrCallableOrRequest, ?string $email = null, ?string $cpfCnpj = null): AccountResponse
    {
        // Se o primeiro parâmetro é um closure, usa builder pattern
        if (is_callable($nameOrCallableOrRequest)) {
            $builder = AccountCreateRequest::builder();
            $nameOrCallableOrRequest($builder);
            $request = $builder->build();
            return $this->resource->createAccount($request->toArray());
        }

        // Se é uma instância de AccountCreateRequest
        if ($nameOrCallableOrRequest instanceof AccountCreateRequest) {
            return $this->resource->createAccount($nameOrCallableOrRequest->toArray());
        }

        // Se é um array
        if (is_array($nameOrCallableOrRequest)) {
            $request = new AccountCreateRequest($nameOrCallableOrRequest);
            return $this->resource->createAccount($request->toArray());
        }

        // Se tem email e cpfCnpj, é criação simples com parâmetros
        if ($email !== null && $cpfCnpj !== null) {
            $request = AccountCreateRequest::create($nameOrCallableOrRequest, $email, $cpfCnpj);
            return $this->resource->createAccount($request->toArray());
        }

        throw new \InvalidArgumentException('Invalid parameters for account creation');
    }

    /**
     * Get account by ID
     */
    public function get(string $id): AccountResponse
    {
        return $this->resource->getAccount($id);
    }

    /**
     * Update account information
     */
    public function update(string $id, array $data): AccountResponse
    {
        return $this->resource->updateAccount($id, $data);
    }

    /**
     * List all accounts with optional filters
     */
    public function list(array $filters = []): array
    {
        return $this->resource->listAccounts($filters);
    }

    /**
     * Delete an account
     */
    public function delete(string $id): array
    {
        return $this->resource->delete($id);
    }
}
