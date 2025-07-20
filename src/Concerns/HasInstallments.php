<?php

namespace Leopaulo88\Asaas\Concerns;

use Leopaulo88\Asaas\Resources\InstallmentResource;

trait HasInstallments
{
    public function installments(): InstallmentResource
    {
        return new InstallmentResource($this->client);
    }
}
