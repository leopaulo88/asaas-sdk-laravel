<?php

namespace Hubooai\Asaas\Tests\Unit\Builders;

use Hubooai\Asaas\Builders\AddressBuilder;

it('it can build complete address', function () {
    $builder = new AddressBuilder();

    $data = $builder
        ->address('Rua das Flores')
        ->addressNumber('123')
        ->complement('Apto 45')
        ->province('Centro')
        ->postalCode('01234-567')
        ->city('São Paulo')
        ->state('SP')
        ->toArray();

    expect($data['address'])->toBe('Rua das Flores')
        ->and($data['addressNumber'])->toBe('123')
        ->and($data['complement'])->toBe('Apto 45')
        ->and($data['province'])->toBe('Centro')
        ->and($data['postalCode'])->toBe('01234-567')
        ->and($data['city'])->toBe('São Paulo')
        ->and($data['state'])->toBe('SP');
});

it('it can build minimal address', function () {
    $builder = new AddressBuilder();
    $data = $builder
        ->address('Rua Principal')
        ->addressNumber('100')
        ->city('Rio de Janeiro')
        ->state('RJ')
        ->toArray();
    expect($data['address'])->toBe('Rua Principal')
        ->and($data['addressNumber'])->toBe('100')
        ->and($data['city'])->toBe('Rio de Janeiro')
        ->and($data['state'])->toBe('RJ')
        ->and($data)->not->toHaveKey('complement')
        ->and($data)->not->toHaveKey('province')
        ->and($data)->not->toHaveKey('postalCode');
});

it('it returns same instance for method chaining', function () {
    $builder = new AddressBuilder();
    $result1 = $builder->address('Rua das Flores');
    $result2 = $builder->addressNumber('123');
    $result3 = $builder->city('São Paulo');
    expect($result1)->toBe($builder)
        ->and($result2)->toBe($builder)
        ->and($result3)->toBe($builder);
});

it('it filters null and empty values', function () {
    $builder = new AddressBuilder();

    $data = $builder
        ->address('Rua das Flores')
        ->addressNumber('123')
        ->complement('')
        ->province(null)
        ->city('São Paulo')
        ->state('SP')
        ->toArray();

    expect($data)->toHaveKey('address')
        ->and($data)->toHaveKey('addressNumber')
        ->and($data)->toHaveKey('city')
        ->and($data)->toHaveKey('state')
        ->and($data)->not->toHaveKey('complement')
        ->and($data)->not->toHaveKey('province');
});
