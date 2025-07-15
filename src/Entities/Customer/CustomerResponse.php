<?php

namespace Leopaulo88\Asaas\Entities\Customer;

use Leopaulo88\Asaas\Concerns\HasAttributes;
use Leopaulo88\Asaas\Concerns\HasFactory;

class CustomerResponse
{
    use HasAttributes, HasFactory;

    // Basic info
    public function getObject(): ?string
    {
        return $this->get('object');
    }

    public function getId(): ?string
    {
        return $this->get('id');
    }

    public function getDateCreated(): ?string
    {
        return $this->get('dateCreated');
    }

    // Customer details
    public function getName(): ?string
    {
        return $this->get('name');
    }

    public function getEmail(): ?string
    {
        return $this->get('email');
    }

    public function getPhone(): ?string
    {
        return $this->get('phone');
    }

    public function getMobilePhone(): ?string
    {
        return $this->get('mobilePhone');
    }

    // Address information
    public function getAddress(): ?string
    {
        return $this->get('address');
    }

    public function getAddressNumber(): ?string
    {
        return $this->get('addressNumber');
    }

    public function getComplement(): ?string
    {
        return $this->get('complement');
    }

    public function getProvince(): ?string
    {
        return $this->get('province');
    }

    public function getCity(): ?int
    {
        return $this->get('city');
    }

    public function getCityName(): ?string
    {
        return $this->get('cityName');
    }

    public function getState(): ?string
    {
        return $this->get('state');
    }

    public function getCountry(): ?string
    {
        return $this->get('country');
    }

    public function getPostalCode(): ?string
    {
        return $this->get('postalCode');
    }

    // Document and identification
    public function getCpfCnpj(): ?string
    {
        return $this->get('cpfCnpj');
    }

    public function getPersonType(): ?string
    {
        return $this->get('personType');
    }

    public function isJuridica(): bool
    {
        return $this->getPersonType() === 'JURIDICA';
    }

    public function isFisica(): bool
    {
        return $this->getPersonType() === 'FISICA';
    }

    // Status and settings
    public function isDeleted(): bool
    {
        return $this->get('deleted', false);
    }

    public function getAdditionalEmails(): ?string
    {
        return $this->get('additionalEmails');
    }

    public function getExternalReference(): ?string
    {
        return $this->get('externalReference');
    }

    public function isNotificationDisabled(): bool
    {
        return $this->get('notificationDisabled', false);
    }

    public function getObservations(): ?string
    {
        return $this->get('observations');
    }

    public function isForeignCustomer(): bool
    {
        return $this->get('foreignCustomer', false);
    }
}
