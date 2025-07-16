<?php

namespace Leopaulo88\Asaas\Entities\Common;

use Leopaulo88\Asaas\Concerns\ValidatesData;
use Leopaulo88\Asaas\Entities\BaseEntity;

class CreditCardHolderInfo extends BaseEntity
{
    use ValidatesData;

    public function __construct(
        public ?string $name = null,
        public ?string $email = null,
        public ?string $cpfCnpj = null,
        public ?string $postalCode = null,
        public ?string $addressNumber = null,
        public ?string $addressComplement = null,
        public ?string $phone = null,
        public ?string $mobilePhone = null

    ) {}

    protected function validationRules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'cpfCnpj' => 'required|string',
            'postalCode' => 'required|string',
            'addressNumber' => 'required|string|max:20',
            'addressComplement' => 'nullable|string',
            'phone' => 'required|string',
            'mobilePhone' => 'nullable|string',
        ];
    }
}
