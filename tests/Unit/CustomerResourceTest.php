<?php

use Illuminate\Support\Facades\Http;
use Leopaulo88\Asaas\Entities\Customer;
use Leopaulo88\Asaas\Entities\Customer\CustomerCreateRequest;
use Leopaulo88\Asaas\Entities\Customer\CustomerUpdateRequest;
use Leopaulo88\Asaas\Entities\Responses\CustomerResponse;
use Leopaulo88\Asaas\Entities\Responses\ListResponse;
use Leopaulo88\Asaas\Http\AsaasClient;
use Leopaulo88\Asaas\Resources\CustomerResource;

beforeEach(function () {
    $this->client = new AsaasClient('test_api_key', 'sandbox');
    $this->customerResource = new CustomerResource($this->client);
});

describe('CustomerResource', function () {

    describe('list method', function () {

        it('should call correct endpoint for listing customers', function () {
            Http::fake();

            $this->customerResource->list();

            Http::assertSent(function ($request) {
                return $request->url() === 'https://sandbox.asaas.com/api/v3/customers'
                    && $request->method() === 'GET';
            });
        });

        it('should pass query parameters correctly', function () {
            Http::fake();

            $params = ['limit' => 10, 'offset' => 0];
            $this->customerResource->list($params);

            Http::assertSent(function ($request) use ($params) {
                return str_contains($request->url(), 'limit=10')
                    && str_contains($request->url(), 'offset=0');
            });
        });

        it('should return List entity with automatic conversion', function () {
            Http::fake([
                'https://sandbox.asaas.com/api/v3/customers' => Http::response([
                    'object' => 'list',
                    'hasMore' => true,
                    'totalCount' => 50,
                    'limit' => 10,
                    'offset' => 0,
                    'data' => [
                        [
                            'object' => 'customer',
                            'id' => 'cus_123',
                            'name' => 'João Silva',
                            'email' => 'joao@teste.com',
                            'cpfCnpj' => '12345678901',
                            'personType' => 'FISICA',
                            'deleted' => false
                        ],
                        [
                            'object' => 'customer',
                            'id' => 'cus_456',
                            'name' => 'Maria Santos',
                            'email' => 'maria@teste.com',
                            'cpfCnpj' => '98765432100',
                            'personType' => 'FISICA',
                            'deleted' => false
                        ]
                    ]
                ])
            ]);

            $result = $this->customerResource->list();

            expect($result)->toBeInstanceOf(ListResponse::class)
                ->and($result->hasMore())->toBe(true)
                ->and($result->getTotalCount())->toBe(50)
                ->and($result->count())->toBe(2);

            // Test automatic entity conversion
            $customers = $result->getData();
            expect($customers)->toHaveCount(2)
                ->and($customers[0])->toBeInstanceOf(CustomerResponse::class)
                ->and($customers[0]->name)->toBe('João Silva')
                ->and($customers[1])->toBeInstanceOf(CustomerResponse::class)
                ->and($customers[1]->name)->toBe('Maria Santos');
        });

        it('should handle empty list correctly', function () {
            Http::fake([
                'https://sandbox.asaas.com/api/v3/customers' => Http::response([
                    'object' => 'list',
                    'hasMore' => false,
                    'totalCount' => 0,
                    'limit' => 10,
                    'offset' => 0,
                    'data' => []
                ])
            ]);

            $result = $this->customerResource->list();

            expect($result)->toBeInstanceOf(ListResponse::class)
                ->and($result->isEmpty())->toBe(true)
                ->and($result->count())->toBe(0)
                ->and($result->getTotalCount())->toBe(0);
        });

    });

    describe('create method', function () {

        it('should create customer with array data', function () {
            $customerData = [
                'name' => 'João Silva',
                'cpfCnpj' => '12345678901',
                'email' => 'joao@teste.com'
            ];

            Http::fake([
                'https://sandbox.asaas.com/api/v3/customers' => Http::response([
                    'object' => 'customer',
                    'id' => 'cus_123',
                    'name' => 'João Silva',
                    'cpfCnpj' => '12345678901',
                    'email' => 'joao@teste.com',
                    'dateCreated' => '2025-01-15',
                    'personType' => 'FISICA',
                    'deleted' => false
                ])
            ]);

            $result = $this->customerResource->create($customerData);

            expect($result)->toBeInstanceOf(CustomerResponse::class)
                ->and($result->name)->toBe('João Silva')
                ->and($result->cpfCnpj)->toBe('12345678901');
        });

        it('should create customer with CustomerCreateRequest', function () {
            $request = Customer::create()
                ->name('Maria Santos')
                ->cpfCnpj('98765432100')
                ->email('maria@teste.com');

            Http::fake([
                'https://sandbox.asaas.com/api/v3/customers' => Http::response([
                    'object' => 'customer',
                    'id' => 'cus_456',
                    'name' => 'Maria Santos',
                    'cpfCnpj' => '98765432100',
                    'email' => 'maria@teste.com',
                    'dateCreated' => '2025-01-15',
                    'personType' => 'FISICA',
                    'deleted' => false
                ])
            ]);

            $result = $this->customerResource->create($request);

            expect($result)->toBeInstanceOf(CustomerResponse::class)
                ->and($result->name)->toBe('Maria Santos');
        });

        it('should send correct headers and data', function () {
            Http::fake();

            $customerData = [
                'name' => 'Test Customer',
                'cpfCnpj' => '12345678901'
            ];

            $this->customerResource->create($customerData);

            Http::assertSent(function ($request) {
                return $request->method() === 'POST'
                    && str_contains($request->url(), '/customers')
                    && $request->header('access_token')[0] === 'test_api_key'
                    && $request->header('Accept')[0] === 'application/json'
                    && $request->header('Content-Type')[0] === 'application/json';
            });
        });

    });

    describe('find method', function () {

        it('should find customer by id', function () {
            $customerId = 'cus_123';

            Http::fake([
                "https://sandbox.asaas.com/api/v3/customers/{$customerId}" => Http::response([
                    'object' => 'customer',
                    'id' => $customerId,
                    'name' => 'Cliente Encontrado',
                    'cpfCnpj' => '12345678901',
                    'dateCreated' => '2025-01-15',
                    'personType' => 'FISICA',
                    'deleted' => false
                ])
            ]);

            $result = $this->customerResource->find($customerId);

            expect($result)->toBeInstanceOf(CustomerResponse::class)
                ->and($result->id)->toBe($customerId)
                ->and($result->name)->toBe('Cliente Encontrado');
        });

        it('should call correct endpoint', function () {
            Http::fake();

            $customerId = 'cus_123';
            $this->customerResource->find($customerId);

            Http::assertSent(function ($request) use ($customerId) {
                return $request->method() === 'GET'
                    && $request->url() === "https://sandbox.asaas.com/api/v3/customers/{$customerId}";
            });
        });

    });

    describe('update method', function () {

        it('should update customer with array data', function () {
            $customerId = 'cus_123';
            $updateData = ['email' => 'novo@email.com'];

            Http::fake([
                "https://sandbox.asaas.com/api/v3/customers/{$customerId}" => Http::response([
                    'object' => 'customer',
                    'id' => $customerId,
                    'name' => 'João Silva',
                    'email' => 'novo@email.com',
                    'cpfCnpj' => '12345678901',
                    'dateCreated' => '2025-01-15',
                    'personType' => 'FISICA',
                    'deleted' => false
                ])
            ]);

            $result = $this->customerResource->update($customerId, $updateData);

            expect($result)->toBeInstanceOf(CustomerResponse::class)
                ->and($result->email)->toBe('novo@email.com');
        });

        it('should update customer with CustomerUpdateRequest', function () {
            $customerId = 'cus_123';
            $request = Customer::update()->email('atualizado@email.com');

            Http::fake([
                "https://sandbox.asaas.com/api/v3/customers/{$customerId}" => Http::response([
                    'object' => 'customer',
                    'id' => $customerId,
                    'email' => 'atualizado@email.com',
                    'dateCreated' => '2025-01-15',
                    'deleted' => false
                ])
            ]);

            $result = $this->customerResource->update($customerId, $request);

            expect($result)->toBeInstanceOf(CustomerResponse::class)
                ->and($result->email)->toBe('atualizado@email.com');
        });

    });

    describe('delete method', function () {

        it('should delete customer and return array', function () {
            $customerId = 'cus_123';

            Http::fake([
                "https://sandbox.asaas.com/api/v3/customers/{$customerId}" => Http::response([
                    'id' => $customerId,
                    'deleted' => true
                ])
            ]);

            $result = $this->customerResource->delete($customerId);

            expect($result)->toBeArray()
                ->and($result['id'])->toBe($customerId)
                ->and($result['deleted'])->toBe(true);
        });

        it('should call DELETE method', function () {
            Http::fake();

            $customerId = 'cus_123';
            $this->customerResource->delete($customerId);

            Http::assertSent(function ($request) use ($customerId) {
                return $request->method() === 'DELETE'
                    && $request->url() === "https://sandbox.asaas.com/api/v3/customers/{$customerId}";
            });
        });

    });

    describe('restore method', function () {

        it('should restore deleted customer', function () {
            $customerId = 'cus_123';

            Http::fake([
                "https://sandbox.asaas.com/api/v3/customers/{$customerId}/restore" => Http::response([
                    'object' => 'customer',
                    'id' => $customerId,
                    'name' => 'Cliente Restaurado',
                    'cpfCnpj' => '12345678901',
                    'dateCreated' => '2025-01-15',
                    'personType' => 'FISICA',
                    'deleted' => false
                ])
            ]);

            $result = $this->customerResource->restore($customerId);

            expect($result)->toBeInstanceOf(CustomerResponse::class)
                ->and($result->id)->toBe($customerId)
                ->and($result->deleted)->toBe(false);
        });

        it('should call correct restore endpoint', function () {
            Http::fake();

            $customerId = 'cus_123';
            $this->customerResource->restore($customerId);

            Http::assertSent(function ($request) use ($customerId) {
                return $request->method() === 'POST'
                    && $request->url() === "https://sandbox.asaas.com/api/v3/customers/{$customerId}/restore";
            });
        });

    });

});
