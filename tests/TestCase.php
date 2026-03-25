<?php

namespace Leopaulo88\Asaas\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use Leopaulo88\Asaas\AsaasServiceProvider;
use Leopaulo88\Asaas\Entities\Account\AccountResponse;
use Leopaulo88\Asaas\Entities\Customer\CustomerResponse;
use Leopaulo88\Asaas\Entities\Installment\InstallmentResponse;
use Leopaulo88\Asaas\Entities\List\ListResponse;
use Leopaulo88\Asaas\Entities\Payment\PaymentResponse;
use Leopaulo88\Asaas\Entities\Subscription\SubscriptionResponse;
use Leopaulo88\Asaas\Entities\Transfer\TransferResponse;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Hubooai\\Asaas\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            AsaasServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');

        config()->set('asaas.entity_mapping', [
            'customer' => CustomerResponse::class,
            'account' => AccountResponse::class,
            'list' => ListResponse::class,
            'payment' => PaymentResponse::class,
            'subscription' => SubscriptionResponse::class,
            'installment' => InstallmentResponse::class,
            'transfer' => TransferResponse::class,
        ]);
    }
}
