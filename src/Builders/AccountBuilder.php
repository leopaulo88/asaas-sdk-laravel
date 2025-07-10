<?php

namespace Hubooai\Asaas\Builders;

use Hubooai\Asaas\Entities\Account\AccountCreateRequest;
use Hubooai\Asaas\Entities\Account\AddressDto;
use Hubooai\Asaas\Entities\Account\CompanyDto;
use Hubooai\Asaas\Entities\Account\TaxDto;
use Hubooai\Asaas\Enums\CompanyType;
use Hubooai\Asaas\Enums\PersonType;

class AccountBuilder
{
    private array $data = [];

    public function name(string $name): self
    {
        $this->data['name'] = $name;
        return $this;
    }

    public function email(string $email): self
    {
        $this->data['email'] = $email;
        return $this;
    }

    public function cpfCnpj(string $cpfCnpj): self
    {
        $this->data['cpfCnpj'] = $cpfCnpj;
        return $this;
    }

    public function companyType(CompanyType $companyType): self
    {
        $this->data['companyType'] = $companyType->value;
        return $this;
    }

    public function phone(?string $phone): self
    {
        $this->data['phone'] = $phone;
        return $this;
    }

    public function mobilePhone(?string $mobilePhone): self
    {
        $this->data['mobilePhone'] = $mobilePhone;
        return $this;
    }

    public function externalReference(?string $externalReference): self
    {
        $this->data['externalReference'] = $externalReference;
        return $this;
    }

    public function notificationDisabled(bool $notificationDisabled = true): self
    {
        $this->data['notificationDisabled'] = $notificationDisabled;
        return $this;
    }

    public function observations(?string $observations): self
    {
        $this->data['observations'] = $observations;
        return $this;
    }

    public function groupName(?string $groupName): self
    {
        $this->data['groupName'] = $groupName;
        return $this;
    }

    public function address(callable $callback): self
    {
        $builder = new AddressBuilder();
        $callback($builder);
        $address = $builder->build();

        $this->data = array_merge($this->data, $address->toArray());
        return $this;
    }

    public function withAddress(AddressDto $address): self
    {
        $this->data = array_merge($this->data, $address->toArray());
        return $this;
    }

    public function build(): AccountCreateRequest
    {
        return new AccountCreateRequest($this->data);
    }

    public function toArray(): array
    {
        return $this->data;
    }
}
