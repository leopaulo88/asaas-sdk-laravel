<?php

namespace Leopaulo88\Asaas\Concerns;

use Leopaulo88\Asaas\Resources\PixResource;

trait HasPix
{
    public function pix(): PixResource
    {
        return new PixResource($this->client);
    }
}
