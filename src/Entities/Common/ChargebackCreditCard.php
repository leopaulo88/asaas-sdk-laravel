<?php

namespace Leopaulo88\Asaas\Entities\Common;

use Leopaulo88\Asaas\Entities\BaseEntity;

class ChargebackCreditCard extends BaseEntity
{
    public function __construct(
        public ?string $number,
        public ?string $brand
    ) {}
}
