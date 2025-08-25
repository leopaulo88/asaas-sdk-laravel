<?php

namespace Leopaulo88\Asaas\Entities\CreditCardToken;

use Leopaulo88\Asaas\Entities\BaseResponse;

class CreditCardTokenResponse extends BaseResponse
{
    public ?string $creditCardNumber;

    public ?string $creditCardBrand;

    public ?string $creditCardToken;
}
