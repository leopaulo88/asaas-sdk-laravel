<?php

namespace Leopaulo88\Asaas\Concerns;

use Leopaulo88\Asaas\Resources\CreditCardResource;

trait HasCreditCards
{
    public function creditCards(): CreditCardResource
    {
        return new CreditCardResource($this->client);
    }
}
