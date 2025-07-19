<?php

namespace Leopaulo88\Asaas\Concerns;

use Leopaulo88\Asaas\Resources\SubscriptionResource;

trait HasSubscriptions
{
    public function subscriptions(): SubscriptionResource
    {
        return new SubscriptionResource($this->client);
    }
}
