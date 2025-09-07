<?php

namespace Leopaulo88\Asaas\Resources;

use Leopaulo88\Asaas\Entities\Pix\CreateKeyResponse;

class PixResource extends BaseResource
{
    public function createKey(): CreateKeyResponse
    {
        $res = $this->post('/pix/addressKeys', ['type' => 'EVP']);

        return CreateKeyResponse::fromArray($res);
    }
}
