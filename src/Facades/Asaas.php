<?php

namespace Hubooai\Asaas\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Hubooai\Asaas\Http\AsaasHttpClient getClient()
 * @method static array info()
 * @method static \Hubooai\Asaas\Resources\CustomerResource customers()
 * @method static \Hubooai\Asaas\Resources\PaymentResource payments()
 * @method static \Hubooai\Asaas\Resources\SubscriptionResource subscriptions()
 * @method static \Hubooai\Asaas\Resources\WebhookResource webhooks()
 *
 * @see \Hubooai\Asaas\Asaas
 */
class Asaas extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'asaas';
    }
}
