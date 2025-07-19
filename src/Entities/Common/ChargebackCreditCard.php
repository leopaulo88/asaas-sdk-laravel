<?php

namespace Leopaulo88\Asaas\Entities\Common;

use Leopaulo88\Asaas\Entities\BaseEntity;
use Leopaulo88\Asaas\Enums\CreditCardBrand;

class ChargebackCreditCard extends BaseEntity
{
    public function __construct(
        public ?string $number,
        public ?CreditCardBrand $brand
    ) {}
}
