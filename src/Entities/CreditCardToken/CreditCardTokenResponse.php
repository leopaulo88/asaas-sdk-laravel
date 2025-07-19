<?php

namespace Leopaulo88\Asaas\Entities\CreditCardToken;

use Leopaulo88\Asaas\Entities\BaseResponse;
use Leopaulo88\Asaas\Enums\CreditCardBrand;

class CreditCardTokenResponse extends BaseResponse
{
    public ?string $creditCardNumber;
    public ?CreditCardBrand $creditCardBrand;
    public ?string $creditCardToken;
}