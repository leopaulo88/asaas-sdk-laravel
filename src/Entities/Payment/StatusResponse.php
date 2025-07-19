<?php

namespace Leopaulo88\Asaas\Entities\Payment;

use Leopaulo88\Asaas\Entities\BaseResponse;
use Leopaulo88\Asaas\Enums\PaymentStatus;

class StatusResponse extends BaseResponse
{
    public ?PaymentStatus $status;
}
