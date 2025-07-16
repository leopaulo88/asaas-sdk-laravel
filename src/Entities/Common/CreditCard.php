<?php

namespace Leopaulo88\Asaas\Entities\Common;

use Leopaulo88\Asaas\Concerns\ValidatesData;
use Leopaulo88\Asaas\Entities\BaseEntity;

class CreditCard extends BaseEntity
{
    use ValidatesData;

    public function __construct(
        public ?string $holderName = null,
        public ?string $number = null,
        public ?string $expiryMonth = null,
        public ?string $expiryYear = null,
        public ?string $ccv = null
    ) {}

    protected function validationRules(): array
    {
        return [
            'holderName' => 'required|string',
            'number' => 'required|string',
            'expiryMonth' => 'required|string',
            'expiryYear' => 'required|string',
            'ccv' => 'required|string',
        ];
    }
}
