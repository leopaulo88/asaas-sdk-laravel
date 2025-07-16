<?php

use Leopaulo88\Asaas\Entities\Account\AccountCreate;
use Leopaulo88\Asaas\Entities\Customer\CustomerCreateEntity;

it('supports all patterns for CustomerCreateEntity', function () {
    // 1. Named Parameters (Padrão mais moderno)
    $customer1 = new CustomerCreateEntity(
        name: 'João Silva',
        email: 'joao@exemplo.com',
        cpfCnpj: '12345678901',
        phone: '11999999999'
    );

    expect($customer1->name)->toBe('João Silva');
    expect($customer1->email)->toBe('joao@exemplo.com');
    expect($customer1->toArray())->toHaveKey('name', 'João Silva');

    // 2. Static Method fromArray (Novo padrão)
    $customer2 = CustomerCreateEntity::fromArray([
        'name' => 'Maria Santos',
        'email' => 'maria@exemplo.com',
        'cpfCnpj' => '98765432100',
        'address' => 'Rua das Flores, 123',
    ]);

    expect($customer2->name)->toBe('Maria Santos');
    expect($customer2->address)->toBe('Rua das Flores, 123');

    // 3. Fluent Interface (Compatibilidade)
    $customer3 = (new CustomerCreateEntity)
        ->name('Pedro Costa')
        ->email('pedro@exemplo.com')
        ->cpfCnpj('11122233344')
        ->phone('11888888888')
        ->address('Av. Principal, 456');

    expect($customer3->name)->toBe('Pedro Costa');
    expect($customer3->phone)->toBe('11888888888');

    // 4. Propriedades Diretas (Novo padrão)
    $customer4 = new CustomerCreateEntity;
    $customer4->name = 'Ana Silva';
    $customer4->email = 'ana@exemplo.com';
    $customer4->cpfCnpj = '55566677788';

    expect($customer4->name)->toBe('Ana Silva');
    expect($customer4->toArray())->toHaveKey('email', 'ana@exemplo.com');

    // 5. Combinando padrões
    $customer5 = (new CustomerCreateEntity(
        name: 'Carlos Lima',
        email: 'carlos@exemplo.com'
    ))
        ->phone('11777777777')
        ->address('Rua Nova, 789');

    $customer5->observations = 'Cliente VIP';

    expect($customer5->name)->toBe('Carlos Lima');
    expect($customer5->phone)->toBe('11777777777');
    expect($customer5->observations)->toBe('Cliente VIP');
});

it('supports all patterns for AccountCreate', function () {
    // 1. Named Parameters
    $account1 = new AccountCreate(
        name: 'Empresa XYZ',
        email: 'contato@xyz.com',
        cpfCnpj: '12.345.678/0001-90',
        companyType: 'LIMITED'
    );

    expect($account1->name)->toBe('Empresa XYZ');
    expect($account1->companyType)->toBe('LIMITED');

    // 2. Static Method fromArray
    $account2 = AccountCreate::fromArray([
        'name' => 'Startup ABC',
        'email' => 'hello@abc.com',
        'cpfCnpj' => '98.765.432/0001-10',
        'site' => 'https://abc.com',
    ]);

    expect($account2->name)->toBe('Startup ABC');
    expect($account2->site)->toBe('https://abc.com');

    // 3. Fluent Interface
    $account3 = (new AccountCreate)
        ->name('Tech Solutions')
        ->email('tech@solutions.com')
        ->cpfCnpj('11.111.111/0001-11')
        ->incomeValue(50000)
        ->webhooks(['PAYMENT_CREATED', 'PAYMENT_UPDATED']);

    expect($account3->name)->toBe('Tech Solutions');
    expect($account3->incomeValue)->toBe(50000);
    expect($account3->webhooks)->toBe(['PAYMENT_CREATED', 'PAYMENT_UPDATED']);

    // 4. Propriedades Diretas
    $account4 = new AccountCreate;
    $account4->name = 'Digital Agency';
    $account4->email = 'contact@agency.com';
    $account4->cpfCnpj = '22.222.222/0001-22';
    $account4->accountManager = 'João Manager';

    expect($account4->name)->toBe('Digital Agency');
    expect($account4->accountManager)->toBe('João Manager');
});

it('maintains backwards compatibility with existing code', function () {
    // Novo padrão: static fromArray
    $customer = CustomerCreateEntity::fromArray([
        'name' => 'Cliente Novo',
        'email' => 'novo@exemplo.com',
        'cpfCnpj' => '12345678901',
    ]);

    expect($customer->name)->toBe('Cliente Novo');

    // Fluent interface continua funcionando
    $customerFluent = (new CustomerCreateEntity)
        ->name('Cliente Fluent')
        ->email('fluent@exemplo.com');

    expect($customerFluent->name)->toBe('Cliente Fluent');
});
