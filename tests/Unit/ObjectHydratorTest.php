<?php

use Leopaulo88\Asaas\Entities\Customer\CustomerResponse;
use Leopaulo88\Asaas\Enums\PersonType;
use Leopaulo88\Asaas\Support\ObjectHydrator;

it('can handle enum properties automatically', function () {
    // Simulando um response com enum
    $mockData = [
        'id' => 'cus_123',
        'name' => 'João Silva',
        'personType' => 'FISICA', // String que será convertida para enum
        'email' => 'joao@exemplo.com',
    ];

    $hydrator = new ObjectHydrator;
    $transformedData = $hydrator->validateAndTransformData($mockData, CustomerResponse::class);

    $customer = new CustomerResponse($transformedData);

    expect($customer->personType)->toBeInstanceOf(PersonType::class);
    expect($customer->personType->value)->toBe('FISICA');
});

it('can handle type casting automatically', function () {
    $mockData = [
        'name' => 'Test Account',
        'incomeValue' => '50000', // String que será convertida para int
        'webhooks' => 'PAYMENT_CREATED', // String que será convertida para array
    ];

    $hydrator = new ObjectHydrator;
    $account = new \Leopaulo88\Asaas\Entities\Account\AccountCreate;
    $hydrator->fillObject($account, $mockData);

    expect($account->name)->toBe('Test Account');
    expect($account->incomeValue)->toBe(50000);
    expect($account->webhooks)->toBe(['PAYMENT_CREATED']);
});

it('can handle nested objects creation', function () {
    // Exemplo de como poderia funcionar com objetos aninhados
    class Address
    {
        public function __construct(
            public ?string $street = null,
            public ?string $number = null,
            public ?string $city = null
        ) {}

        public static function fromArray(array $data): self
        {
            return new self(
                street: $data['street'] ?? null,
                number: $data['number'] ?? null,
                city: $data['city'] ?? null
            );
        }
    }

    class PersonWithAddress
    {
        public function __construct(
            public ?string $name = null,
            public ?Address $address = null
        ) {}
    }

    $mockData = [
        'name' => 'Maria Silva',
        'address' => [
            'street' => 'Rua das Flores',
            'number' => '123',
            'city' => 'São Paulo',
        ],
    ];

    $hydrator = new ObjectHydrator;
    $person = new PersonWithAddress;
    $hydrator->fillObject($person, $mockData);

    expect($person->name)->toBe('Maria Silva');
    expect($person->address)->toBeInstanceOf(Address::class);
    expect($person->address->street)->toBe('Rua das Flores');
    expect($person->address->number)->toBe('123');
    expect($person->address->city)->toBe('São Paulo');
});

it('handles failed object creation gracefully', function () {
    class NonCreatableClass
    {
        private function __construct()
        {
            // Private constructor para simular falha
        }
    }

    class TestClass
    {
        public function __construct(
            public ?NonCreatableClass $nonCreatable = null
        ) {}
    }

    $mockData = [
        'nonCreatable' => ['some' => 'data'],
    ];

    $hydrator = new ObjectHydrator;
    $instance = new TestClass;

    // Não deve lançar exception, deve retornar null para propriedades nullable
    // quando a criação do objeto falha
    $hydrator->fillObject($instance, $mockData);

    expect($instance->nonCreatable)->toBeNull();
});

it('can get property type information', function () {
    $hydrator = new ObjectHydrator;

    $typeInfo = $hydrator->getPropertyType(CustomerResponse::class, 'personType');

    expect($typeInfo)->toBeArray();
    expect($typeInfo['type'])->toBe(PersonType::class);
    expect($typeInfo['nullable'])->toBeTrue();

    $nameTypeInfo = $hydrator->getPropertyType(CustomerResponse::class, 'name');
    expect($nameTypeInfo['type'])->toBe('string');
});
