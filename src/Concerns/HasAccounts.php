<?php

namespace Leopaulo88\Asaas\Concerns;

use Leopaulo88\Asaas\Resources\AccountResource;

trait HasAccounts
{
    public function accounts(): AccountResource
    {
        return new AccountResource($this->client);
    }
}
