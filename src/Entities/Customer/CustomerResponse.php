<?php

namespace Leopaulo88\AsaasSdkLaravel\Entities\Customer;

use Leopaulo88\AsaasSdkLaravel\Concerns\HasAttributes;
use Leopaulo88\AsaasSdkLaravel\Concerns\HasFactory;

class CustomerResponse
{
    use HasAttributes, HasFactory;

    // All basic functionality now comes from traits
    // Constructor, get(), toArray(), fromArray(), fromResponse() are inherited

    public function getId(): ?string
    {
        return $this->get('id');
    }

    public function getName(): ?string
    {
        return $this->get('name');
    }

    public function getEmail(): ?string
    {
        return $this->get('email');
    }

    public function getCpfCnpj(): ?string
    {
        return $this->get('cpfCnpj');
    }

    public function getPhone(): ?string
    {
        return $this->get('phone');
    }

    public function getMobilePhone(): ?string
    {
        return $this->get('mobilePhone');
    }

    public function getDateCreated(): ?string
    {
        return $this->get('dateCreated');
    }

    public function getDateUpdated(): ?string
    {
        return $this->get('dateUpdated');
    }

    public function getObject(): ?string
    {
        return $this->get('object');
    }

    public function isDeleted(): bool
    {
        return $this->get('deleted', false);
    }
}
