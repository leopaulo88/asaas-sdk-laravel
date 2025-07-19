<?php

namespace Leopaulo88\Asaas\Entities\Common;

use Leopaulo88\Asaas\Entities\BaseEntity;

class CreditCardHolderInfo extends BaseEntity
{
    public function __construct(
        public ?string $name = null,
        public ?string $email = null,
        public ?string $cpfCnpj = null,
        public ?string $postalCode = null,
        public ?string $addressNumber = null,
        public ?string $addressComplement = null,
        public ?string $phone = null,
        public ?string $mobilePhone = null

    )
    {
    }

}