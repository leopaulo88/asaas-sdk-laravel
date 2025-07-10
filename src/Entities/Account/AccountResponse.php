<?php

namespace Hubooai\Asaas\Entities\Account;

class AccountResponse
{
    public string $object;
    public string $id;
    public string $name;
    public string $email;
    public string $phone;
    public string $mobilePhone;
    public string $cpfCnpj;
    public string $personType;
    public string $companyType;
    public string $observations;
    public ?string $externalReference;
    public ?string $groupName;
    public string $apiKey;
    
    // DTOs para organizar dados relacionados
    public AddressDto $address;
    public BankAccountDto $bankAccount;
    public CompanyDto $company;
    public TaxDto $tax;
    public PixDto $pix;

    public function __construct(array $data)
    {
        // Campos básicos
        $this->object = $data['object'] ?? '';
        $this->id = $data['id'] ?? '';
        $this->name = $data['name'] ?? '';
        $this->email = $data['email'] ?? '';
        $this->phone = $data['phone'] ?? '';
        $this->mobilePhone = $data['mobilePhone'] ?? '';
        $this->cpfCnpj = $data['cpfCnpj'] ?? '';
        $this->personType = $data['personType'] ?? '';
        $this->companyType = $data['companyType'] ?? '';
        $this->observations = $data['observations'] ?? '';
        $this->externalReference = $data['externalReference'] ?? null;
        $this->groupName = $data['groupName'] ?? null;
        $this->apiKey = $data['apiKey'] ?? '';

        // DTOs organizados
        $this->address = AddressDto::fromArray($data);
        $this->bankAccount = BankAccountDto::fromArray($data);
        $this->company = CompanyDto::fromArray($data);
        $this->tax = TaxDto::fromArray($data);
        $this->pix = PixDto::fromArray($data);
    }

    public static function fromArray(array $data): self
    {
        return new self($data);
    }

    public function toArray(): array
    {
        return array_merge(
            [
                'object' => $this->object,
                'id' => $this->id,
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone,
                'mobilePhone' => $this->mobilePhone,
                'cpfCnpj' => $this->cpfCnpj,
                'personType' => $this->personType,
                'companyType' => $this->companyType,
                'observations' => $this->observations,
                'externalReference' => $this->externalReference,
                'groupName' => $this->groupName,
                'apiKey' => $this->apiKey,
            ],
            $this->address->toArray(),
            $this->bankAccount->toArray(),
            $this->company->toArray(),
            $this->tax->toArray(),
            $this->pix->toArray()
        );
    }
}
