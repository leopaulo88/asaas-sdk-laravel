<?php

use Leopaulo88\Asaas\Asaas;
use Leopaulo88\Asaas\Entities\Account\AccountCreate;
use Leopaulo88\Asaas\Entities\Account\AccountResponse;

beforeEach(function () {
    $this->asaas = new Asaas('test_api_key', 'sandbox');
});

it('can create account with array data', function () {
    $accountData = [
        'name' => 'João Silva',
        'email' => 'joao@exemplo.com',
        'cpfCnpj' => '12345678901',
        'birthDate' => '1990-01-01',
        'phone' => '11999999999',
    ];

    $mockResponse = [
        'object' => 'account',
        'id' => 'acc_123456789',
        'name' => 'João Silva',
        'email' => 'joao@exemplo.com',
        'loginEmail' => 'joao@exemplo.com',
        'phone' => '11999999999',
        'mobilePhone' => '',
        'address' => '',
        'addressNumber' => '',
        'complement' => '',
        'province' => '',
        'postalCode' => '',
        'cpfCnpj' => '12345678901',
        'birthDate' => '1990-01-01',
        'personType' => 'FISICA',
        'companyType' => null,
        'city' => 'São Paulo',
        'state' => 'SP',
        'country' => 'Brasil',
        'site' => null,
        'walletId' => [],
        'apiKey' => 'test_api_key_subaccount',
        'dateCreated' => '2024-01-01',
    ];

    Http::fake([
        'sandbox.asaas.com/api/v3/accounts' => Http::response($mockResponse, 200),
    ]);

    $result = $this->asaas->accounts()->create($accountData);

    expect($result)->toBeInstanceOf(AccountResponse::class)
        ->and($result->id)->toBe('acc_123456789')
        ->and($result->name)->toBe('João Silva')
        ->and($result->email)->toBe('joao@exemplo.com');
});

it('can create account with fluent interface', function () {
    $accountRequest = (new AccountCreate())
        ->name('Maria Santos')
        ->email('maria@exemplo.com')
        ->cpfCnpj('98765432100')
        ->birthDate('1985-05-15')
        ->phone('11888888888')
        ->site('https://maria.com');

    $mockResponse = [
        'object' => 'account',
        'id' => 'acc_987654321',
        'name' => 'Maria Santos',
        'email' => 'maria@exemplo.com',
        'loginEmail' => 'maria@exemplo.com',
        'phone' => '11888888888',
        'mobilePhone' => '',
        'address' => '',
        'addressNumber' => '',
        'complement' => '',
        'province' => '',
        'postalCode' => '',
        'cpfCnpj' => '98765432100',
        'birthDate' => '1985-05-15',
        'personType' => 'FISICA',
        'companyType' => null,
        'city' => 'São Paulo',
        'state' => 'SP',
        'country' => 'Brasil',
        'site' => 'https://maria.com',
        'walletId' => [],
        'apiKey' => 'test_api_key_subaccount_2',
        'dateCreated' => '2024-01-01',
    ];

    Http::fake([
        'sandbox.asaas.com/api/v3/accounts' => Http::response($mockResponse, 200),
    ]);

    $result = $this->asaas->accounts()->create($accountRequest);

    expect($result)->toBeInstanceOf(AccountResponse::class)
        ->and($result->id)->toBe('acc_987654321')
        ->and($result->name)->toBe('Maria Santos')
        ->and($result->site)->toBe('https://maria.com');
});

it('can find account by id', function () {
    $mockResponse = [
        'object' => 'account',
        'id' => 'acc_123456789',
        'name' => 'João Silva',
        'email' => 'joao@exemplo.com',
        'loginEmail' => 'joao@exemplo.com',
        'phone' => '11999999999',
        'mobilePhone' => '',
        'address' => '',
        'addressNumber' => '',
        'complement' => '',
        'province' => '',
        'postalCode' => '',
        'cpfCnpj' => '12345678901',
        'birthDate' => '1990-01-01',
        'personType' => 'FISICA',
        'companyType' => null,
        'city' => 'São Paulo',
        'state' => 'SP',
        'country' => 'Brasil',
        'site' => null,
        'walletId' => [],
        'apiKey' => 'test_api_key_subaccount',
        'dateCreated' => '2024-01-01',
    ];

    Http::fake([
        'sandbox.asaas.com/api/v3/accounts/acc_123456789' => Http::response($mockResponse, 200),
    ]);

    $result = $this->asaas->accounts()->find('acc_123456789');

    expect($result)->toBeInstanceOf(AccountResponse::class)
        ->and($result->id)->toBe('acc_123456789')
        ->and($result->name)->toBe('João Silva');
});

it('can list accounts', function () {
    $mockResponse = [
        'object' => 'list',
        'hasMore' => false,
        'totalCount' => 2,
        'limit' => 20,
        'offset' => 0,
        'data' => [
            [
                'object' => 'account',
                'id' => 'acc_123456789',
                'name' => 'João Silva',
                'email' => 'joao@exemplo.com',
                'loginEmail' => 'joao@exemplo.com',
                'phone' => '11999999999',
                'mobilePhone' => '',
                'address' => '',
                'addressNumber' => '',
                'complement' => '',
                'province' => '',
                'postalCode' => '',
                'cpfCnpj' => '12345678901',
                'birthDate' => '1990-01-01',
                'personType' => 'FISICA',
                'companyType' => null,
                'city' => 'São Paulo',
                'state' => 'SP',
                'country' => 'Brasil',
                'site' => null,
                'walletId' => [],
                'apiKey' => 'test_api_key_subaccount',
                'dateCreated' => '2024-01-01',
            ],
            [
                'object' => 'account',
                'id' => 'acc_987654321',
                'name' => 'Maria Santos',
                'email' => 'maria@exemplo.com',
                'loginEmail' => 'maria@exemplo.com',
                'phone' => '11888888888',
                'mobilePhone' => '',
                'address' => '',
                'addressNumber' => '',
                'complement' => '',
                'province' => '',
                'postalCode' => '',
                'cpfCnpj' => '98765432100',
                'birthDate' => '1985-05-15',
                'personType' => 'FISICA',
                'companyType' => null,
                'city' => 'São Paulo',
                'state' => 'SP',
                'country' => 'Brasil',
                'site' => null,
                'walletId' => [],
                'apiKey' => 'test_api_key_subaccount_2',
                'dateCreated' => '2024-01-01',
            ],
        ],
    ];

    Http::fake([
        'sandbox.asaas.com/api/v3/accounts*' => Http::response($mockResponse, 200),
    ]);

    $result = $this->asaas->accounts()->list();

    expect($result->object)->toBe('list')
        ->and($result->totalCount)->toBe(2)
        ->and($result->getData())->toHaveCount(2)
        ->and($result->getData()[0])->toBeInstanceOf(AccountResponse::class)
        ->and($result->getData()[0]->id)->toBe('acc_123456789');
});

it('can get current account information', function () {
    $mockResponse = [
        'object' => 'account',
        'id' => 'acc_main',
        'name' => 'Conta Principal',
        'email' => 'principal@exemplo.com',
        'loginEmail' => 'principal@exemplo.com',
        'phone' => '11777777777',
        'mobilePhone' => '',
        'address' => 'Rua Principal, 123',
        'addressNumber' => '123',
        'complement' => 'Sala 1',
        'province' => 'Centro',
        'postalCode' => '01234-567',
        'cpfCnpj' => '12.345.678/0001-90',
        'birthDate' => null,
        'personType' => 'JURIDICA',
        'companyType' => 'LIMITED',
        'city' => 'São Paulo',
        'state' => 'SP',
        'country' => 'Brasil',
        'site' => 'https://exemplo.com',
        'walletId' => [],
        'apiKey' => 'main_api_key',
        'dateCreated' => '2023-01-01',
    ];

    Http::fake([
        'sandbox.asaas.com/api/v3/myAccount' => Http::response($mockResponse, 200),
    ]);

    $result = $this->asaas->accounts()->me();

    expect($result)->toBeInstanceOf(AccountResponse::class);
    expect($result->id)->toBe('acc_main');
    expect($result->name)->toBe('Conta Principal');
    expect($result->personType)->toBe('JURIDICA');
    expect($result->companyType)->toBe('LIMITED');
});
