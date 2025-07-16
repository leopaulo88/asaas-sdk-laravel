<?php

use Leopaulo88\Asaas\Entities\Customer\CustomerResponse;
use Leopaulo88\Asaas\Entities\List\ListResponse;
use Leopaulo88\Asaas\Support\EntityFactory;

beforeEach(function () {
    // Não precisa mais de initialize() - o mapeamento é estático
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
