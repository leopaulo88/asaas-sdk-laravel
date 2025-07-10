<?php

namespace Hubooai\Asaas\Entities\Account;

class AddressDto
{
    public string $address;
    public string $addressNumber;
    public ?string $complement;
    public string $province;
    public string $postalCode;
    public string $city;
    public string $state;
    public string $country;

    public function __construct(array $data = [])
    {
        $this->address = $data['address'] ?? '';
        $this->addressNumber = $data['addressNumber'] ?? '';
        $this->complement = $data['complement'] ?? null;
        $this->province = $data['province'] ?? '';
        $this->postalCode = $data['postalCode'] ?? '';
        $this->city = $data['city'] ?? '';
        $this->state = $data['state'] ?? '';
        $this->country = $data['country'] ?? 'Brasil';
    }

    public static function fromArray(array $data): self
    {
        return new self($data);
    }

    public function toArray(): array
    {
        return [
            'address' => $this->address,
            'addressNumber' => $this->addressNumber,
            'complement' => $this->complement,
            'province' => $this->province,
            'postalCode' => $this->postalCode,
            'city' => $this->city,
            'state' => $this->state,
            'country' => $this->country,
        ];
    }

    /**
     * Create address with all required fields
     */
    public static function create(
        string $address,
        string $addressNumber,
        string $province,
        string $postalCode,
        string $city,
        string $state,
        ?string $complement = null,
        string $country = 'Brasil'
    ): self {
        return new self([
            'address' => $address,
            'addressNumber' => $addressNumber,
            'complement' => $complement,
            'province' => $province,
            'postalCode' => $postalCode,
            'city' => $city,
            'state' => $state,
            'country' => $country,
        ]);
    }

    /**
     * Create a builder instance for fluent interface
     */
    public static function builder(): \Hubooai\Asaas\Builders\AddressBuilder
    {
        return new \Hubooai\Asaas\Builders\AddressBuilder();
    }
}
