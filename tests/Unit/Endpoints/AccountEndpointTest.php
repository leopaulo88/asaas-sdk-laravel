<?php

use Hubooai\Asaas\Endpoints\AccountEndpoint;
use Hubooai\Asaas\Entities\Account\AccountCreateRequest;
use Hubooai\Asaas\Entities\Account\AccountResponse;
use Hubooai\Asaas\Resources\AccountResource;

beforeEach(function () {
    $this->mockResource = Mockery::mock(AccountResource::class);
    $this->endpoint = new AccountEndpoint($this->mockResource);
});

afterEach(function () {
    Mockery::close();
});

it('can create account with simple parameters', function () {
    $expectedData = [
        'name' => 'João da Silva',
        'email' => 'joao@exemplo.com',
        'cpfCnpj' => '12345678901',
    ];

    $expectedResponse = new AccountResponse([
        'object' => 'account',
        'id' => 'acc_123456789',
        'name' => 'João da Silva',
        'email' => 'joao@exemplo.com',
        'cpfCnpj' => '12345678901',
    ]);

    $this->mockResource
        ->shouldReceive('createAccount')
        ->once()
        ->with($expectedData)
        ->andReturn($expectedResponse);

    $result = $this->endpoint->create('João da Silva', 'joao@exemplo.com', '12345678901');

    expect($result)->toBeInstanceOf(AccountResponse::class)
        ->and($result->id)->toBe('acc_123456789')
        ->and($result->name)->toBe('João da Silva');
});

it('can create account with array', function () {
    $inputData = [
        'name' => 'João da Silva',
        'email' => 'joao@exemplo.com',
        'cpfCnpj' => '12345678901',
        'phone' => '11999999999',
        'address' => 'Rua das Flores',
        'addressNumber' => '123',
        'city' => 'São Paulo',
        'state' => 'SP',
    ];

    $expectedResponse = new AccountResponse([
        'object' => 'account',
        'id' => 'acc_123456789',
        'name' => 'João da Silva',
        'email' => 'joao@exemplo.com',
    ]);

    $this->mockResource
        ->shouldReceive('createAccount')
        ->once()
        ->with($inputData)
        ->andReturn($expectedResponse);

    $result = $this->endpoint->create($inputData);

    expect($result)->toBeInstanceOf(AccountResponse::class)
        ->and($result->id)->toBe('acc_123456789');
});

it('can create account with dto', function () {
    $request = new AccountCreateRequest([
        'name' => 'João da Silva',
        'email' => 'joao@exemplo.com',
        'cpfCnpj' => '12345678901',
    ]);

    $expectedResponse = new AccountResponse([
        'object' => 'account',
        'id' => 'acc_123456789',
        'name' => 'João da Silva',
        'email' => 'joao@exemplo.com',
    ]);

    $this->mockResource
        ->shouldReceive('createAccount')
        ->once()
        ->with($request->toArray())
        ->andReturn($expectedResponse);

    $result = $this->endpoint->create($request);

    expect($result)->toBeInstanceOf(AccountResponse::class)
        ->and($result->id)->toBe('acc_123456789');
});

it('can create account with builder pattern', function () {
    $expectedData = [
        'name' => 'João da Silva',
        'email' => 'joao@exemplo.com',
        'cpfCnpj' => '12345678901',
        'phone' => '11999999999',
        'mobilePhone' => '11888888888',
        'address' => 'Rua das Flores',
        'addressNumber' => '123',
        'complement' => 'Apto 45',
        'province' => 'Centro',
        'postalCode' => '01234-567',
        'city' => 'São Paulo',
        'state' => 'SP',
    ];

    $expectedResponse = new AccountResponse([
        'object' => 'account',
        'id' => 'acc_123456789',
        'name' => 'João da Silva',
        'email' => 'joao@exemplo.com',
    ]);

    $this->mockResource
        ->shouldReceive('createAccount')
        ->once()
        ->with(Mockery::subset($expectedData))
        ->andReturn($expectedResponse);

    $result = $this->endpoint->create(function ($builder) {
        $builder
            ->name('João da Silva')
            ->email('joao@exemplo.com')
            ->cpfCnpj('12345678901')
            ->phone('11999999999')
            ->mobilePhone('11888888888')
            ->address(function ($address) {
                $address
                    ->address('Rua das Flores')
                    ->addressNumber('123')
                    ->complement('Apto 45')
                    ->province('Centro')
                    ->postalCode('01234-567')
                    ->city('São Paulo')
                    ->state('SP');
            });
    });

    expect($result)->toBeInstanceOf(AccountResponse::class)
        ->and($result->id)->toBe('acc_123456789');
});

it('throws exception for invalid create parameters', function () {
    $this->expectException(InvalidArgumentException::class);
    $this->expectExceptionMessage('Invalid parameters for account creation');

    $this->endpoint->create('Only name without email and cpf');
});

it('can get account by id', function () {
    $accountId = 'acc_123456789';
    $expectedResponse = new AccountResponse([
        'object' => 'account',
        'id' => $accountId,
        'name' => 'João da Silva',
        'email' => 'joao@exemplo.com',
    ]);

    $this->mockResource
        ->shouldReceive('getAccount')
        ->once()
        ->with($accountId)
        ->andReturn($expectedResponse);

    $result = $this->endpoint->get($accountId);

    expect($result)->toBeInstanceOf(AccountResponse::class)
        ->and($result->id)->toBe($accountId)
        ->and($result->name)->toBe('João da Silva');
});

it('can update account', function () {
    $accountId = 'acc_123456789';
    $updateData = [
        'name' => 'João Silva Updated',
        'email' => 'joao.updated@exemplo.com',
    ];

    $expectedResponse = new AccountResponse([
        'object' => 'account',
        'id' => $accountId,
        'name' => 'João Silva Updated',
        'email' => 'joao.updated@exemplo.com',
    ]);

    $this->mockResource
        ->shouldReceive('updateAccount')
        ->once()
        ->with($accountId, $updateData)
        ->andReturn($expectedResponse);

    $result = $this->endpoint->update($accountId, $updateData);

    expect($result)->toBeInstanceOf(AccountResponse::class)
        ->and($result->id)->toBe($accountId)
        ->and($result->name)->toBe('João Silva Updated')
        ->and($result->email)->toBe('joao.updated@exemplo.com');
});

it('can list accounts without filters', function () {
    $expectedResponse = [
        'object' => 'list',
        'hasMore' => false,
        'totalCount' => 2,
        'limit' => 10,
        'offset' => 0,
        'data' => [
            [
                'object' => 'account',
                'id' => 'acc_123456789',
                'name' => 'João da Silva',
                'email' => 'joao@exemplo.com',
            ],
            [
                'object' => 'account',
                'id' => 'acc_987654321',
                'name' => 'Maria Silva',
                'email' => 'maria@exemplo.com',
            ],
        ],
    ];

    $this->mockResource
        ->shouldReceive('listAccounts')
        ->once()
        ->with([])
        ->andReturn($expectedResponse);

    $result = $this->endpoint->list();

    expect($result)->toBeArray()
        ->and($result['object'])->toBe('list')
        ->and($result['data'])->toHaveCount(2);
});

it('can list accounts with filters', function () {
    $filters = [
        'offset' => 10,
        'limit' => 5,
    ];

    $expectedResponse = [
        'object' => 'list',
        'hasMore' => true,
        'totalCount' => 25,
        'limit' => 5,
        'offset' => 10,
        'data' => [],
    ];

    $this->mockResource
        ->shouldReceive('listAccounts')
        ->once()
        ->with($filters)
        ->andReturn($expectedResponse);

    $result = $this->endpoint->list($filters);

    expect($result)->toBeArray()
        ->and($result['limit'])->toBe(5)
        ->and($result['offset'])->toBe(10)
        ->and($result['hasMore'])->toBeTrue();
});

it('can delete account', function () {
    $accountId = 'acc_123456789';
    $expectedResponse = [
        'object' => 'account',
        'id' => $accountId,
        'deleted' => true,
    ];

    $this->mockResource
        ->shouldReceive('delete')
        ->once()
        ->with($accountId)
        ->andReturn($expectedResponse);

    $result = $this->endpoint->delete($accountId);

    expect($result)->toBeArray()
        ->and($result['id'])->toBe($accountId)
        ->and($result['deleted'])->toBeTrue();
});
