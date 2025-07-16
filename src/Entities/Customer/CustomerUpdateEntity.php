<?php

namespace Leopaulo88\Asaas\Entities\Customer;

use Leopaulo88\Asaas\Entities\BaseEntity;

class CustomerUpdateEntity extends BaseEntity
{
    public function __construct(
        public ?string $name = null,
        public ?string $cpfCnpj = null,
        public ?string $email = null,
        public ?string $phone = null,
        public ?string $mobilePhone = null,
        public ?string $address = null,
        public ?string $addressNumber = null,
        public ?string $complement = null,
        public ?string $province = null,
        public ?string $postalCode = null,
        public ?string $externalReference = null,
        public ?bool $notificationDisabled = null,
        public ?string $additionalEmails = null,
        public ?string $municipalInscription = null,
        public ?string $stateInscription = null,
        public ?string $observations = null,
        public ?string $groupName = null,
        public ?string $company = null,
        public ?bool $foreignCustomer = null
    ) {}

    public function name(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function cpfCnpj(string $cpfCnpj): self
    {
        $this->cpfCnpj = $cpfCnpj;

        return $this;
    }

    public function email(string $email): self
    {
        $this->email = $email;

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

    public function address(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function addressNumber(string $number): self
    {
        $this->addressNumber = $number;

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

    public function externalReference(string $reference): self
    {
        $this->externalReference = $reference;

        return $this;
    }

    public function disableNotifications(bool $disabled = true): self
    {
        $this->notificationDisabled = $disabled;

        return $this;
    }

    public function additionalEmails(string $emails): self
    {
        $this->additionalEmails = $emails;

        return $this;
    }

    public function municipalInscription(string $inscription): self
    {
        $this->municipalInscription = $inscription;

        return $this;
    }

    public function stateInscription(string $inscription): self
    {
        $this->stateInscription = $inscription;

        return $this;
    }

    public function observations(string $observations): self
    {
        $this->observations = $observations;

        return $this;
    }

    public function groupName(string $groupName): self
    {
        $this->groupName = $groupName;

        return $this;
    }

    public function company(string $company): self
    {
        $this->company = $company;

        return $this;
    }

    public function foreignCustomer(bool $foreign = true): self
    {
        $this->foreignCustomer = $foreign;

        return $this;
    }

    protected function transform(): void
    {
        if (
            $this->cpfCnpj !== null
        ) {
            $this->cpfCnpj = preg_replace('/\D/', '', $this->cpfCnpj);
        }

        if (
            $this->postalCode !== null
        ) {
            $this->postalCode = preg_replace('/\D/', '', $this->postalCode);
        }
    }
}
