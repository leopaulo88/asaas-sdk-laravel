<?php

namespace Leopaulo88\Asaas\Concerns;

use Leopaulo88\Asaas\Resources\TransferResource;

trait HasTransfers
{
    public function transfers(): TransferResource
    {
        return new TransferResource($this->client);
    }
}
