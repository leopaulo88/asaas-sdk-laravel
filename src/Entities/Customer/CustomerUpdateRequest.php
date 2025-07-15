<?php

namespace Leopaulo88\AsaasSdkLaravel\Entities\Customer;

use Leopaulo88\AsaasSdkLaravel\Concerns\ValidatesData;
use Leopaulo88\AsaasSdkLaravel\Contracts\RequestBuilderInterface;
use Leopaulo88\AsaasSdkLaravel\Enums\BrazilianState;
use Leopaulo88\AsaasSdkLaravel\Enums\PersonType;
use Illuminate\Validation\Rule;

class CustomerUpdateRequest implements RequestBuilderInterface
{
    use ValidatesData;

    protected array $data;

    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    // Fluent methods for updating customer data (all optional)
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

    public function cpfCnpj(string $cpfCnpj): self
    {
        $this->data['cpfCnpj'] = $cpfCnpj;
        return $this;
    }

    /**
     * Update complete address information
     */
    public function address(string $address, ?string $number = null, ?string $complement = null): self
    {
        $this->data['address'] = $address;
        if ($number) $this->data['addressNumber'] = $number;
        if ($complement) $this->data['complement'] = $complement;
        return $this;
    }

    /**
     * Update location with enum validation for Brazilian states
     */
    public function location(string $city, string|BrazilianState $state, ?string $postalCode = null): self
    {
        $this->data['city'] = $city;
        $this->data['state'] = $state instanceof BrazilianState ? $state->value : $state;
        if ($postalCode) $this->data['postalCode'] = $postalCode;
        return $this;
    }

    public function externalReference(string $reference): self
    {
        $this->data['externalReference'] = $reference;
        return $this;
    }

    /**
     * Update notification settings
     */
    public function disableNotifications(bool $disabled = true): self
    {
        $this->data['notificationDisabled'] = $disabled;
        return $this;
    }

    public function observations(string $observations): self
    {
        $this->data['observations'] = $observations;
        return $this;
    }

    /**
     * Convert to array with validation and transformation
     */
    public function toArray(): array
    {
        $this->validate();
        $this->transform();
        return array_filter($this->data, fn($value) => $value !== null);
    }

    /**
     * Get validation rules for update (all fields optional)
     */
    protected function validationRules(): array
    {
        return [
            'name' => 'nullable|string|max:100',
            'email' => 'nullable|email|max:100',
            'phone' => 'nullable|string|max:20',
            'mobilePhone' => 'nullable|string|max:20',
            'cpfCnpj' => 'nullable|string|min:11|max:14',
            'postalCode' => 'nullable|string|size:8',
            'address' => 'nullable|string|max:100',
            'addressNumber' => 'nullable|string|max:10',
            'complement' => 'nullable|string|max:50',
            'province' => 'nullable|string|max:50',
            'city' => 'nullable|string|max:50',
            'state' => [
                'nullable',
                'string',
                'size:2',
                Rule::in(array_column(BrazilianState::cases(), 'value'))
            ],
            'country' => 'nullable|string|size:2',
            'externalReference' => 'nullable|string|max:100',
            'notificationDisabled' => 'nullable|boolean',
            'additionalEmails' => 'nullable|string|max:200',
            'municipalInscription' => 'nullable|string|max:20',
            'stateInscription' => 'nullable|string|max:20',
            'observations' => 'nullable|string|max:255',
        ];
    }

    /**
     * Get custom validation messages
     */
    protected function validationMessages(): array
    {
        return [
            'name.max' => 'Customer name cannot exceed 100 characters',
            'email.email' => 'Please provide a valid email address',
            'cpfCnpj.min' => 'CPF must have 11 digits or CNPJ must have 14 digits',
            'cpfCnpj.max' => 'CPF must have 11 digits or CNPJ must have 14 digits',
            'state.in' => 'Please provide a valid Brazilian state code',
            'state.size' => 'State must be exactly 2 characters',
            'country.size' => 'Country must be exactly 2 characters',
            'postalCode.size' => 'Postal code must be exactly 8 digits',
        ];
    }

    /**
     * Validate business rules (no required fields for updates)
     */
    protected function validate(): void
    {
        if (isset($this->data['email']) && !filter_var($this->data['email'], FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException('Invalid email format');
        }

        // Validate Brazilian state if provided
        if (isset($this->data['state']) && !BrazilianState::isValid($this->data['state'])) {
            throw new \InvalidArgumentException('Invalid Brazilian state code');
        }

        // Validate CPF/CNPJ format if provided
        if (isset($this->data['cpfCnpj'])) {
            $cleanDocument = preg_replace('/\D/', '', $this->data['cpfCnpj']);
            if (!in_array(strlen($cleanDocument), [11, 14])) {
                throw new \InvalidArgumentException('CPF must have 11 digits or CNPJ must have 14 digits');
            }
        }
    }

    /**
     * Transform data for API compatibility
     */
    protected function transform(): void
    {
        // Clean CPF/CNPJ - remove all non-numeric characters
        if (isset($this->data['cpfCnpj'])) {
            $this->data['cpfCnpj'] = preg_replace('/\D/', '', $this->data['cpfCnpj']);
        }

        // Clean postal code
        if (isset($this->data['postalCode'])) {
            $this->data['postalCode'] = preg_replace('/\D/', '', $this->data['postalCode']);
        }

        // Ensure state is uppercase
        if (isset($this->data['state'])) {
            $this->data['state'] = strtoupper($this->data['state']);
        }
    }

    /**
     * Get the person type based on document
     */
    public function getPersonType(): ?PersonType
    {
        if (!isset($this->data['cpfCnpj'])) {
            return null;
        }

        return PersonType::fromDocument($this->data['cpfCnpj']);
    }
}
