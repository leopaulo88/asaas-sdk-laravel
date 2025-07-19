<?php

namespace Leopaulo88\Asaas\Entities\Account;

use Leopaulo88\Asaas\Entities\BaseEntity;

class AccountCreate extends BaseEntity
{
    public function __construct(
        public ?string $name = null,
        public ?string $email = null,
        public ?string $cpfCnpj = null,
        public ?string $birthDate = null,
        public ?string $companyType = null,
        public ?string $phone = null,
        public ?string $mobilePhone = null,
        public ?string $site = null,
        public ?int $incomeValue = null,
        public ?string $address = null,
        public ?string $addressNumber = null,
        public ?string $complement = null,
        public ?string $province = null,
        public ?string $postalCode = null,
        public ?array $webhooks = null,
        public ?string $accountManager = null
    ) {}

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

    public function birthDate(string $birthDate): self
    {
        $this->birthDate = $birthDate;
        return $this;
    }

    public function companyType(string $companyType): self
    {
        $this->companyType = $companyType;
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

    public function site(string $site): self
    {
        $this->site = $site;
        return $this;
    }

    public function incomeValue(int $incomeValue): self
    {
        $this->incomeValue = $incomeValue;
        return $this;
    }

    public function address(string $address): self
    {
        $this->address = $address;
        return $this;
    }

    public function addressNumber(string $addressNumber): self
    {
        $this->addressNumber = $addressNumber;
        return $this;
    }

    public function complement(string $complement): self
    {
        $this->complement = $complement;
        return $this;
    }

    public function province(string $province): self
    {
        $this->province = $province;
        return $this;
    }

    public function postalCode(string $postalCode): self
    {
        $this->postalCode = $postalCode;
        return $this;
    }

    public function webhooks(array $webhooks): self
    {
        $this->webhooks = $webhooks;
        return $this;
    }

    public function accountManager(string $accountManager): self
    {
        $this->accountManager = $accountManager;
        return $this;
    }
}
