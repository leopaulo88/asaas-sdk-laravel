<?php

use Hubooai\Asaas\Entities\Account\AccountCreateRequest;

it('can be created with minimum required fields', function () {
    $data = [
        'name' => 'João da Silva',
        'email' => 'joao@exemplo.com',
        'cpfCnpj' => '12345678901',
    ];
    $request = new AccountCreateRequest($data);
    expect($request->name)->toBe('João da Silva')
        ->and($request->email)->toBe('joao@exemplo.com')
        ->and($request->cpfCnpj)->toBe('12345678901');
});

it('can be created with all fields', function () {
    $data = [
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
        'tradingName' => 'Empresa LTDA',
        'businessActivity' => 'Desenvolvimento de software',
    ];
    $request = new AccountCreateRequest($data);
    expect($request->name)->toBe('João da Silva')
        ->and($request->email)->toBe('joao@exemplo.com')
        ->and($request->cpfCnpj)->toBe('12345678901')
        ->and($request->phone)->toBe('11999999999')
        ->and($request->mobilePhone)->toBe('11888888888')
        ->and($request->address)->toBe('Rua das Flores')
        ->and($request->addressNumber)->toBe('123')
        ->and($request->complement)->toBe('Apto 45')
        ->and($request->province)->toBe('Centro')
        ->and($request->postalCode)->toBe('01234-567')
        ->and($request->city)->toBe('São Paulo')
        ->and($request->state)->toBe('SP')
        ->and($request->tradingName)->toBe('Empresa LTDA')
        ->and($request->businessActivity)->toBe('Desenvolvimento de software');
});

it('it can be created using static create method', function () {
    $request = AccountCreateRequest::create(
        'João da Silva',
        'joao@exemplo.com',
        '12345678901'
    );
    expect($request->name)->toBe('João da Silva')
        ->and($request->email)->toBe('joao@exemplo.com')
        ->and($request->cpfCnpj)->toBe('12345678901');
});

it('it can convert to array', function () {
    $data = [
        'name' => 'João da Silva',
        'email' => 'joao@exemplo.com',
        'cpfCnpj' => '12345678901',
        'phone' => '11999999999',
    ];
    $request = new AccountCreateRequest($data);
    $array = $request->toArray();
    expect($array['name'])->toBe($data['name'])
        ->and($array['email'])->toBe($data['email'])
        ->and($array['cpfCnpj'])->toBe($data['cpfCnpj'])
        ->and($array['phone'])->toBe($data['phone']);
});

it('can get builder instance', function () {
    $builder = AccountCreateRequest::builder();
    expect($builder)->toBeInstanceOf(\Hubooai\Asaas\Builders\AccountBuilder::class);
});

it('filters null values in to array', function () {
    $request = new AccountCreateRequest([
        'name' => 'João da Silva',
        'email' => 'joao@exemplo.com',
        'cpfCnpj' => '12345678901',
        'phone' => null,
        'mobilePhone' => '',
    ]);
    $array = $request->toArray();
    expect($array)->toHaveKey('name')
        ->and($array)->toHaveKey('email')
        ->and($array)->toHaveKey('cpfCnpj')
        ->and($array)->not->toHaveKey('phone')
        ->and($array)->not->toHaveKey('mobilePhone');
});
