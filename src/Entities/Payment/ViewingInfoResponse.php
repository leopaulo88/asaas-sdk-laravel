<?php

namespace Leopaulo88\Asaas\Entities\Payment;

use Carbon\Carbon;
use Leopaulo88\Asaas\Entities\BaseResponse;

class ViewingInfoResponse extends BaseResponse
{
    public ?Carbon $invoiceViewedDate;

    public ?Carbon $boletoViewedDate;
}
