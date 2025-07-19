<?php

namespace Leopaulo88\Asaas\Entities\Payment;

use Leopaulo88\Asaas\Entities\BaseResponse;
use Leopaulo88\Asaas\Entities\Common\BankSlip;
use Leopaulo88\Asaas\Entities\Common\Pix;
use Leopaulo88\Asaas\Entities\CreditCardToken\CreditCardTokenResponse;

class BillingInfoResponse extends BaseResponse
{
    public ?Pix $pix;

    public ?CreditCardTokenResponse $creditCard;

    public ?BankSlip $bankSlip;
}
