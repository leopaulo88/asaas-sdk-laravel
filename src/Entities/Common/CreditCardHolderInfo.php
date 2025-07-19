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

    public function name(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function email(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function cpfCnpj(string $cpfCnpj): self
    {
        $this->cpfCnpj = $cpfCnpj;
        return $this;
    }

    public function postalCode(string $postalCode): self
    {
        $this->postalCode = $postalCode;
        return $this;
    }

    public function addressNumber(string $addressNumber): self
    {
        $this->addressNumber = $addressNumber;
        return $this;
    }

    public function addressComplement(string $addressComplement): self
    {
        $this->addressComplement = $addressComplement;
        return $this;
    }

    public function phone(string $phone): self
    {
        $this->phone = $phone;
        return $this;
    }

    public function mobilePhone(string $mobilePhone): self
    {
        $this->mobilePhone = $mobilePhone;
        return $this;
    }
}