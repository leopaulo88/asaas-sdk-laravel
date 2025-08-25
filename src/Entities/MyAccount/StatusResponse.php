<?php

namespace Leopaulo88\Asaas\Entities\MyAccount;

use Leopaulo88\Asaas\Entities\BaseResponse;

class StatusResponse extends BaseResponse
{
    public ?string $id;

    public ?string $commercialInfo;

    public ?string $bankAccountInfo;

    public ?string $documentation;

    public ?string $general;
}
