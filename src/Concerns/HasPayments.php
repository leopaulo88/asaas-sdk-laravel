<?php

namespace Leopaulo88\Asaas\Concerns;

use Leopaulo88\Asaas\Resources\PaymentResource;

trait HasPayments
{
    public function payments(): PaymentResource
    {
        return new PaymentResource($this->client);
    }
}
