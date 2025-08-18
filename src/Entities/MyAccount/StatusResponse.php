<?php

namespace Leopaulo88\Asaas\Entities\MyAccount;

use Leopaulo88\Asaas\Entities\BaseResponse;
use Leopaulo88\Asaas\Enums\MyAccountStatus;

class StatusResponse extends BaseResponse
{
    public ?string $id;

    public ?MyAccountStatus $commercialInfo;

    public ?MyAccountStatus $bankAccountInfo;

    public ?MyAccountStatus $documentation;

    public ?MyAccountStatus $general;
}
