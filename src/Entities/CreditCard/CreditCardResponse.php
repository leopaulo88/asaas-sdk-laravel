<?php

namespace Leopaulo88\Asaas\Entities\CreditCard;

use Leopaulo88\Asaas\Entities\BaseResponse;
use Leopaulo88\Asaas\Enums\CreditCardBrand;

class CreditCardResponse extends BaseResponse
{
    public ?string $creditCardNumber;
    public ?CreditCardBrand $creditCardBrand;
    public ?string $creditCardToken;
}