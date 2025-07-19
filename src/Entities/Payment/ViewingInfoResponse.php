<?php

namespace Leopaulo88\Asaas\Entities\Payment;

use Carbon\Carbon;
use Leopaulo88\Asaas\Entities\BaseResponse;
use Leopaulo88\Asaas\Entities\Common\BankSlip;
use Leopaulo88\Asaas\Entities\Common\Pix;
use Leopaulo88\Asaas\Entities\CreditCardToken\CreditCardTokenResponse;

class ViewingInfoResponse extends BaseResponse
{
    public ?Carbon $invoiceViewedDate;
    public ?Carbon $boletoViewedDate;
}
