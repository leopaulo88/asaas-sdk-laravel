<?php

use Leopaulo88\Asaas\Entities\Responses\CustomerResponse;
use Leopaulo88\Asaas\Factories\EntityFactory;

beforeEach(function () {
    // Limpar mapeamentos existentes para teste isolado
    $reflection = new ReflectionClass(EntityFactory::class);
    $property = $reflection->getProperty('entityMap');
    $property->setAccessible(true);
    $originalMap = $property->getValue();

    // Usar apenas mapeamentos básicos para testes
    $property->setValue([
        'customer' => CustomerResponse::class,
    ]);

    // Guardar o mapa original para restaurar depois
    $this->originalMap = $originalMap;
});

afterEach(function () {
    // Restaurar o mapa original após cada teste
    $reflection = new ReflectionClass(EntityFactory::class);
    $property = $reflection->getProperty('entityMap');
    $property->setAccessible(true);
    $property->setValue($this->originalMap);
});

describe('EntityFactory', function () {

    describe('createFromArray', function () {

        it('creates customer entity automatically', function () {
            $customerData = [
                'object' => 'customer',
                'id' => 'cus_123456789',
                'name' => 'João Silva',
                'email' => 'joao@example.com',
                'cpfCnpj' => '12345678901',
                'personType' => 'FISICA',
            ];

            $entity = EntityFactory::createFromArray($customerData);

            expect($entity)->toBeInstanceOf(CustomerResponse::class)
                ->and($entity->name)->toBe('João Silva')
                ->and($entity->email)->toBe('joao@example.com');
        });

        it('returns array for unknown object type', function () {
            $unknownData = [
                'object' => 'unknown_type',
                'id' => 'unk_123',
                'name' => 'Unknown Entity',
            ];

            $result = EntityFactory::createFromArray($unknownData);

            expect($result)->toBeArray()
                ->and($result['object'])->toBe('unknown_type');
        });

        it('returns array when no object field', function () {
            $dataWithoutObject = [
                'id' => 'some_id',
                'name' => 'Some Name',
            ];

            $result = EntityFactory::createFromArray($dataWithoutObject);

            expect($result)->toBeArray()
                ->and($result['id'])->toBe('some_id');
        });

    });

    describe('entity registration', function () {

        it('can register new entity type', function () {
            EntityFactory::registerEntity('custom_type', CustomerResponse::class);

            expect(EntityFactory::isRegistered('custom_type'))->toBe(true);

            // Limpar para não afetar outros testes
            EntityFactory::unregisterEntity('custom_type');
        });

    });

});
