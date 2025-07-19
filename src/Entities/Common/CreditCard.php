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

    public function holderName(string $holderName): self
    {
        $this->holderName = $holderName;
        return $this;
    }

    public function number(string $number): self
    {
        $this->number = $number;
        return $this;
    }

    public function expiryMonth(string $expiryMonth): self
    {
        $this->expiryMonth = $expiryMonth;
        return $this;
    }

    public function expiryYear(string $expiryYear): self
    {
        $this->expiryYear = $expiryYear;
        return $this;
    }

    public function ccv(string $ccv): self
    {
        $this->ccv = $ccv;
        return $this;
    }
}