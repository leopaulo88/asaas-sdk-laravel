<?php

namespace Leopaulo88\Asaas\Entities\Common;

use Leopaulo88\Asaas\Entities\BaseEntity;

class CreditCard extends BaseEntity
{

    public function __construct(
        public ?string $holderName = null,
        public ?string $number = null,
        public ?string $expiryMonth = null,
        public ?string $expiryYear = null,
        public ?string $ccv = null
    )
    {
    }
}