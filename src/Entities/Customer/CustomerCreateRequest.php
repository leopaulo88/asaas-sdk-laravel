<?php

namespace Leopaulo88\Asaas\Entities\Customer;

use Leopaulo88\Asaas\Concerns\ValidatesData;
use Leopaulo88\Asaas\Contracts\RequestBuilderInterface;

class CustomerCreateRequest implements RequestBuilderInterface
{
    use ValidatesData;

    protected array $data;

    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    public function name(string $name): self
    {
        $this->data['name'] = $name;
        return $this;
    }

    public function cpfCnpj(string $cpfCnpj): self
    {
        $this->data['cpfCnpj'] = $cpfCnpj;
        return $this;
    }

    public function email(string $email): self
    {
        $this->data['email'] = $email;
        return $this;
    }

    public function phone(string $phone): self
    {
        $this->data['phone'] = $phone;
        return $this;
    }

    public function mobilePhone(string $mobilePhone): self
    {
        $this->data['mobilePhone'] = $mobilePhone;
        return $this;
    }

    public function address(string $address): self
    {
        $this->data['address'] = $address;
        return $this;
    }

    public function addressNumber(string $number): self
    {
        $this->data['addressNumber'] = $number;
        return $this;
    }

    public function complement(string $complement): self
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

    public function externalReference(string $reference): self
    {
        $this->data['externalReference'] = $reference;
        return $this;
    }

    public function disableNotifications(bool $disabled = true): self
    {
        $this->data['notificationDisabled'] = $disabled;
        return $this;
    }

    public function additionalEmails(string $emails): self
    {
        $this->data['additionalEmails'] = $emails;
        return $this;
    }

    public function municipalInscription(string $inscription): self
    {
        $this->data['municipalInscription'] = $inscription;
        return $this;
    }

    public function stateInscription(string $inscription): self
    {
        $this->data['stateInscription'] = $inscription;
        return $this;
    }

    public function observations(string $observations): self
    {
        $this->data['observations'] = $observations;
        return $this;
    }

    public function groupName(string $groupName): self
    {
        $this->data['groupName'] = $groupName;
        return $this;
    }

    public function company(string $company): self
    {
        $this->data['company'] = $company;
        return $this;
    }

    public function foreignCustomer(bool $foreign = true): self
    {
        $this->data['foreignCustomer'] = $foreign;
        return $this;
    }

    public function toArray(): array
    {
        $this->validate();
        $this->transform();
        return array_filter($this->data, fn($value) => $value !== null);
    }

    protected function validationRules(): array
    {
        return [
            'name' => 'required|string',
            'cpfCnpj' => 'required|string',
            'email' => 'nullable|email',
        ];
    }

    protected function validationMessages(): array
    {
        return [
            'name.required' => 'Customer name is required',
            'cpfCnpj.required' => 'CPF/CNPJ is required',
            'email.email' => 'Please provide a valid email address',
        ];
    }

    protected function transform(): void
    {
        // Clean CPF/CNPJ - remove non-numeric characters
        if (isset($this->data['cpfCnpj'])) {
            $this->data['cpfCnpj'] = preg_replace('/\D/', '', $this->data['cpfCnpj']);
        }

        if (isset($this->data['postalCode'])) {
            $this->data['postalCode'] = preg_replace('/\D/', '', $this->data['postalCode']);
        }
    }
}
