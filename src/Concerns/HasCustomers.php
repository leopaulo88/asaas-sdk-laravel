<?php

namespace Leopaulo88\Asaas\Concerns;

use Leopaulo88\Asaas\Resources\CustomerResource;

trait HasCustomers
{
    public function customers(): CustomerResource
    {
        return new CustomerResource($this->client);
    }
}
