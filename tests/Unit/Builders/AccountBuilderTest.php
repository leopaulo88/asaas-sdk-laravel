<?php

use Hubooai\Asaas\Builders\AccountBuilder;
use Hubooai\Asaas\Entities\Account\AccountCreateRequest;

it('it can build account with basic fields', function () {
    $builder = new AccountBuilder();
    $request = $builder
        ->name('João da Silva')
        ->email('joao@exemplo.com')
        ->cpfCnpj('12345678901')
        ->build();
    expect($request)->toBeInstanceOf(AccountCreateRequest::class)
        ->and($request->name)->toBe('João da Silva')
        ->and($request->email)->toBe('joao@exemplo.com')
        ->and($request->cpfCnpj)->toBe('12345678901');
});

it('it can build account with contact fields', function () {
    $builder = new AccountBuilder();
    $request = $builder
        ->name('João da Silva')
        ->email('joao@exemplo.com')
        ->cpfCnpj('12345678901')
        ->phone('11999999999')
        ->mobilePhone('11888888888')
        ->build();
    expect($request->phone)->toBe('11999999999')
        ->and($request->mobilePhone)->toBe('11888888888');
});

it('it can build account with address using closure', function () {
    $builder = new AccountBuilder();
    $request = $builder
        ->name('João da Silva')
        ->email('joao@exemplo.com')
        ->cpfCnpj('12345678901')
        ->address(function ($address) {
            $address
                ->address('Rua das Flores')
                ->addressNumber('123')
                ->complement('Apto 45')
                ->province('Centro')
                ->postalCode('01234-567')
                ->city('São Paulo')
                ->state('SP');
        })
        ->build();
    expect($request->address)->toBe('Rua das Flores')
        ->and($request->addressNumber)->toBe('123')
        ->and($request->complement)->toBe('Apto 45')
        ->and($request->province)->toBe('Centro')
        ->and($request->postalCode)->toBe('01234-567')
        ->and($request->city)->toBe('São Paulo')
        ->and($request->state)->toBe('SP');
});

it('it can build account with company data using closure', function () {
    $builder = new AccountBuilder();
    $request = $builder
        ->name('João da Silva')
        ->email('joao@exemplo.com')
        ->cpfCnpj('12345678901')
        ->company(function ($company) {
            $company
                ->name('Empresa LTDA')
                ->tradingName('Empresa')
                ->businessActivity('Desenvolvimento de software');
        })
        ->build();
    expect($request->companyName)->toBe('Empresa LTDA')
        ->and($request->tradingName)->toBe('Empresa')
        ->and($request->businessActivity)->toBe('Desenvolvimento de software');
});

it('it can chain all methods', function () {
    $builder = new AccountBuilder();
    $request = $builder
        ->name('João da Silva')
        ->email('joao@exemplo.com')
        ->cpfCnpj('12345678901')
        ->phone('11999999999')
        ->mobilePhone('11888888888')
        ->birthDate('1990-01-01')
        ->address(function ($address) {
            $address
                ->address('Rua das Flores')
                ->addressNumber('123')
                ->city('São Paulo')
                ->state('SP');
        })
        ->company(function ($company) {
            $company
                ->name('Empresa LTDA')
                ->tradingName('Empresa');
        })
        ->build();
    $array = $request->toArray();
    expect($array)->toHaveKey('name')
        ->and($array)->toHaveKey('email')
        ->and($array)->toHaveKey('cpfCnpj')
        ->and($array)->toHaveKey('phone')
        ->and($array)->toHaveKey('mobilePhone')
        ->and($array)->toHaveKey('birthDate')
        ->and($array)->toHaveKey('address')
        ->and($array)->toHaveKey('addressNumber')
        ->and($array)->toHaveKey('city')
        ->and($array)->toHaveKey('state')
        ->and($array)->toHaveKey('companyName')
        ->and($array)->toHaveKey('tradingName');
});

it('it returns same instance for method chaining', function () {
    $builder = new AccountBuilder();
    $result1 = $builder->name('João');
    $result2 = $builder->email('joao@exemplo.com');
    $result3 = $builder->cpfCnpj('12345678901');
    expect($result1)->toBe($builder)
        ->and($result2)->toBe($builder)
        ->and($result3)->toBe($builder);
});
