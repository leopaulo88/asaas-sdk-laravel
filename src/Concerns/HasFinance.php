<?php

namespace Leopaulo88\Asaas\Concerns;

use Leopaulo88\Asaas\Resources\FinanceResource;

trait HasFinance
{
    public function finance(): FinanceResource
    {
        return new FinanceResource($this->client);
    }
}
