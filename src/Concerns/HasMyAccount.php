<?php

namespace Leopaulo88\Asaas\Concerns;

use Leopaulo88\Asaas\Resources\MyAccountResource;

trait HasMyAccount
{
    public function myAccount(): MyAccountResource
    {
        return new MyAccountResource($this->client);
    }
}
