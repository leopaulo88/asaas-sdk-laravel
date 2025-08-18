<?php

namespace Leopaulo88\Asaas\Resources;

use Leopaulo88\Asaas\Entities\MyAccount\StatusResponse;

class MyAccountResource extends BaseResource
{
    public function status(): StatusResponse
    {
        $res = $this->get('/myAccount/status');

        return StatusResponse::fromArray($res);
    }
}
