<?php

namespace Leopaulo88\Asaas\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use Leopaulo88\Asaas\AsaasServiceProvider;
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
            'customer' => \Leopaulo88\Asaas\Entities\Customer\CustomerResponse::class,
            'account' => \Leopaulo88\Asaas\Entities\Account\AccountResponse::class,
            'list' => \Leopaulo88\Asaas\Entities\List\ListResponse::class,
            'payment' => \Leopaulo88\Asaas\Entities\Payment\PaymentResponse::class,
            'subscription' => \Leopaulo88\Asaas\Entities\Subscription\SubscriptionResponse::class,
            'installment' => \Leopaulo88\Asaas\Entities\Installment\InstallmentResponse::class,
        ]);
    }
}
