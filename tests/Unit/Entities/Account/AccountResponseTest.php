<?php


use Hubooai\Asaas\Entities\Account\AccountResponse;
use Hubooai\Asaas\Enums\PersonType;
use Hubooai\Asaas\Enums\CompanyType;

it('can be created with basic data', function () {
    $data = [
        'object' => 'account',
        'id' => 'acc_123456789',
        'name' => 'João da Silva',
        'email' => 'joao@exemplo.com',
        'loginEmail' => 'joao@exemplo.com',
        'cpfCnpj' => '12345678901',
    ];
    $response = new AccountResponse($data);
    expect($response->object)->toBe('account')
        ->and($response->id)->toBe('acc_123456789')
        ->and($response->name)->toBe('João da Silva')
        ->and($response->email)->toBe('joao@exemplo.com')
        ->and($response->loginEmail)->toBe('joao@exemplo.com')
        ->and($response->cpfCnpj)->toBe('12345678901');
});

it('can be created with complete data', function () {
    $data = [
        'object' => 'account',
        'id' => 'acc_123456789',
        'name' => 'João da Silva',
        'email' => 'joao@exemplo.com',
        'loginEmail' => 'joao@exemplo.com',
        'phone' => '11999999999',
        'mobilePhone' => '11888888888',
        'address' => 'Rua das Flores',
        'addressNumber' => '123',
        'complement' => 'Apto 45',
        'province' => 'Centro',
        'postalCode' => '01234-567',
        'cpfCnpj' => '12345678901',
        'birthDate' => '1990-01-01',
        'personType' => 'FISICA',
        'companyType' => null,
        'city' => 'São Paulo',
        'state' => 'SP',
        'country' => 'Brasil',
        'tradingName' => 'Empresa LTDA',
        'businessActivity' => 'Desenvolvimento de software',
        'walletId' => 'wallet_123456789',
    ];
    $response = new AccountResponse($data);
    expect($response->object)->toBe('account')
        ->and($response->id)->toBe('acc_123456789')
        ->and($response->name)->toBe('João da Silva')
        ->and($response->email)->toBe('joao@exemplo.com')
        ->and($response->phone)->toBe('11999999999')
        ->and($response->mobilePhone)->toBe('11888888888')
        ->and($response->address)->toBe('Rua das Flores')
        ->and($response->addressNumber)->toBe('123')
        ->and($response->complement)->toBe('Apto 45')
        ->and($response->province)->toBe('Centro')
        ->and($response->postalCode)->toBe('01234-567')
        ->and($response->cpfCnpj)->toBe('12345678901')
        ->and($response->birthDate)->toBe('1990-01-01')
        ->and($response->personType)->toBe(PersonType::FISICA)
        ->and($response->companyType)->toBeNull()
        ->and($response->city)->toBe('São Paulo')
        ->and($response->state)->toBe('SP')
        ->and($response->country)->toBe('Brasil')
        ->and($response->tradingName)->toBe('Empresa LTDA')
        ->and($response->businessActivity)->toBe('Desenvolvimento de software')
        ->and($response->walletId)->toBe('wallet_123456789');
});

it('handles person type enum correctly', function () {
    $data = [
        'object' => 'account',
        'id' => 'acc_123456789',
        'name' => 'Empresa LTDA',
        'email' => 'contato@empresa.com',
        'cpfCnpj' => '12345678000123',
        'personType' => 'JURIDICA',
    ];
    $response = new AccountResponse($data);
    expect($response->personType)->toBe(PersonType::JURIDICA);
});

it('handles company type enum correctly', function () {
    $data = [
        'object' => 'account',
        'id' => 'acc_123456789',
        'name' => 'Empresa LTDA',
        'email' => 'contato@empresa.com',
        'cpfCnpj' => '12345678000123',
        'companyType' => 'LIMITED',
    ];
    $response = new AccountResponse($data);
    expect($response->companyType)->toBe(CompanyType::LIMITED);
});

it('can convert to array', function () {
    $originalData = [
        'object' => 'account',
        'id' => 'acc_123456789',
        'name' => 'João da Silva',
        'email' => 'joao@exemplo.com',
        'cpfCnpj' => '12345678901',
        'personType' => 'FISICA',
    ];
    $response = new AccountResponse($originalData);
    $array = $response->toArray();
    expect($array['object'])->toBe('account')
        ->and($array['id'])->toBe('acc_123456789')
        ->and($array['name'])->toBe('João da Silva')
        ->and($array['email'])->toBe('joao@exemplo.com')
        ->and($array['cpfCnpj'])->toBe('12345678901')
        ->and($array['personType'])->toBe('FISICA');
});

it('filters null values in toArray', function () {
    $data = [
        'object' => 'account',
        'id' => 'acc_123456789',
        'name' => 'João da Silva',
        'email' => 'joao@exemplo.com',
        'cpfCnpj' => '12345678901',
        'phone' => null,
        'mobilePhone' => '',
        'complement' => null,
    ];
    $response = new AccountResponse($data);
    $array = $response->toArray();
    expect($array)->toHaveKey('object')
        ->and($array)->toHaveKey('id')
        ->and($array)->toHaveKey('name')
        ->and($array)->toHaveKey('email')
        ->and($array)->toHaveKey('cpfCnpj')
        ->and($array)->not->toHaveKey('phone')
        ->and($array)->not->toHaveKey('mobilePhone')
        ->and($array)->not->toHaveKey('complement');
});
