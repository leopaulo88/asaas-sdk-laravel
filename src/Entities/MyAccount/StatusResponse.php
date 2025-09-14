<?php

namespace Leopaulo88\Asaas\Entities\MyAccount;

use Leopaulo88\Asaas\Entities\BaseResponse;

class StatusResponse extends BaseResponse
{
    public ?string $id = null;

    public ?string $commercialInfo = null;

    public ?string $bankAccountInfo = null;

    public ?string $documentation = null;

    public ?string $general = null;
}
