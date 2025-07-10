<?php

namespace Hubooai\Asaas\Entities\Account;

class AccountCreateRequest
{
    public string $name;
    public string $email;
    public string $cpfCnpj;
    public ?string $companyType = null;
    public ?string $phone = null;
    public ?string $mobilePhone = null;
    public ?string $externalReference = null;
    public ?bool $notificationDisabled = null;
    public ?string $observations = null;
    public ?string $groupName = null;

    // DTOs para organizar dados relacionados no request
    public ?AddressDto $address = null;
    public ?CompanyDto $company = null;
    public ?TaxDto $tax = null;

    public function __construct(array $data = [])
    {
        // Campos básicos
        $this->name = $data['name'] ?? '';
        $this->email = $data['email'] ?? '';
        $this->cpfCnpj = $data['cpfCnpj'] ?? '';
        $this->companyType = $data['companyType'] ?? null;
        $this->phone = $data['phone'] ?? null;
        $this->mobilePhone = $data['mobilePhone'] ?? null;
        $this->externalReference = $data['externalReference'] ?? null;
        $this->notificationDisabled = $data['notificationDisabled'] ?? null;
        $this->observations = $data['observations'] ?? null;
        $this->groupName = $data['groupName'] ?? null;

        // DTOs organizados (apenas se houver dados)
        if ($this->hasAddressData($data)) {
            $this->address = AddressDto::fromArray($data);
        }

        if ($this->hasCompanyData($data)) {
            $this->company = CompanyDto::fromArray($data);
        }

        if ($this->hasTaxData($data)) {
            $this->tax = TaxDto::fromArray($data);
        }
    }

    /**
     * Create account request with minimum required fields
     */
    public static function create(string $name, string $email, string $cpfCnpj): self
    {
        return new self([
            'name' => $name,
            'email' => $email,
            'cpfCnpj' => $cpfCnpj,
        ]);
    }

    /**
     * Create a builder instance for fluent interface
     */
    public static function builder(): \Hubooai\Asaas\Builders\AccountBuilder
    {
        return new \Hubooai\Asaas\Builders\AccountBuilder();
    }

    /**
     * Set address information
     */
    public function withAddress(AddressDto $address): self
    {
        $this->address = $address;
        return $this;
    }

    /**
     * Set company information
     */
    public function withCompany(CompanyDto $company): self
    {
        $this->company = $company;
        return $this;
    }

    /**
     * Set tax information
     */
    public function withTax(TaxDto $tax): self
    {
        $this->tax = $tax;
        return $this;
    }

    private function hasAddressData(array $data): bool
    {
        return !empty($data['address']) || !empty($data['postalCode']) || !empty($data['city']);
    }

    private function hasCompanyData(array $data): bool
    {
        return !empty($data['companyName']) || !empty($data['tradingName']);
    }

    private function hasTaxData(array $data): bool
    {
        return !empty($data['municipalInscription']) || !empty($data['stateInscription']);
    }

    public function toArray(): array
    {
        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'cpfCnpj' => $this->cpfCnpj,
        ];

        // Adicionar campos opcionais apenas se não forem null
        foreach (['companyType', 'phone', 'mobilePhone', 'externalReference', 'notificationDisabled', 'observations', 'groupName'] as $field) {
            if ($this->$field !== null) {
                $data[$field] = $this->$field;
            }
        }

        // Mesclar dados dos DTOs se existirem
        if ($this->address !== null) {
            $data = array_merge($data, $this->address->toArray());
        }

        if ($this->company !== null) {
            $data = array_merge($data, $this->company->toArray());
        }

        if ($this->tax !== null) {
            $data = array_merge($data, $this->tax->toArray());
        }

        return array_filter($data, fn($value) => $value !== null && $value !== '');
    }
}
