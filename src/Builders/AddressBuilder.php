<?php

namespace Hubooai\Asaas\Builders;

use Hubooai\Asaas\Entities\Account\AddressDto;

class AddressBuilder
{
    private array $data = [];

    public function address(string $address): self
    {
        $this->data['address'] = $address;
        return $this;
    }

    public function addressNumber(string $addressNumber): self
    {
        $this->data['addressNumber'] = $addressNumber;
        return $this;
    }

    public function complement(?string $complement): self
    {
        $this->data['complement'] = $complement;
        return $this;
    }

    public function province(string $province): self
    {
        $this->data['province'] = $province;
        return $this;
    }

    public function postalCode(string $postalCode): self
    {
        $this->data['postalCode'] = $postalCode;
        return $this;
    }

    public function city(string $city): self
    {
        $this->data['city'] = $city;
        return $this;
    }

    public function state(string $state): self
    {
        $this->data['state'] = $state;
        return $this;
    }

    public function country(string $country): self
    {
        $this->data['country'] = $country;
        return $this;
    }

    public function build(): AddressDto
    {
        return AddressDto::fromArray($this->data);
    }
}
