<?php

use Illuminate\Support\Facades\Config;
use Leopaulo88\Asaas\Entities\Customer\CustomerResponse;
use Leopaulo88\Asaas\Entities\List\ListResponse;
use Leopaulo88\Asaas\Support\EntityFactory;

beforeEach(function () {
    // Backup da configuração original
    $this->originalMapping = Config::get('asaas.entity_mapping', []);

    // Garantir que temos os mappings básicos para os testes
    if (empty($this->originalMapping)) {
        Config::set('asaas.entity_mapping', [
            'customer' => CustomerResponse::class,
            'list' => ListResponse::class,
        ]);
    }
});

afterEach(function () {
    // Restaurar a configuração original após cada teste
    if (isset($this->originalMapping)) {
        Config::set('asaas.entity_mapping', $this->originalMapping);
    }
});

describe('EntityFactory', function () {

    describe('basic functionality', function () {

        it('has correct mappings registered', function () {
            expect(EntityFactory::isRegistered('list'))->toBe(true)
                ->and(EntityFactory::isRegistered('customer'))->toBe(true);

            expect(EntityFactory::getEntityClass('list'))->toBe(ListResponse::class)
                ->and(EntityFactory::getEntityClass('customer'))->toBe(CustomerResponse::class);
        });

    });

    describe('entity registration', function () {

        it('can register additional entities', function () {
            EntityFactory::registerEntity('custom_type', CustomerResponse::class);

            expect(EntityFactory::isRegistered('custom_type'))->toBe(true)
                ->and(EntityFactory::getEntityClass('custom_type'))->toBe(CustomerResponse::class);

            EntityFactory::unregisterEntity('custom_type');
            expect(EntityFactory::isRegistered('custom_type'))->toBe(false);
        });

    });

});
