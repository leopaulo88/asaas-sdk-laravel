<?php

namespace Leopaulo88\AsaasSdkLaravel\Entities\Customer;

use Leopaulo88\AsaasSdkLaravel\Concerns\ValidatesData;
use Leopaulo88\AsaasSdkLaravel\Contracts\RequestBuilderInterface;
use Leopaulo88\AsaasSdkLaravel\Enums\BrazilianState;
use Leopaulo88\AsaasSdkLaravel\Enums\PersonType;
use Illuminate\Validation\Rule;

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

    public function address(string $address, ?string $number = null, ?string $complement = null): self
    {
        $this->data['address'] = $address;
        if ($number) $this->data['addressNumber'] = $number;
        if ($complement) $this->data['complement'] = $complement;
        return $this;
    }

    /**
     * Set location with enum validation for Brazilian states
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
     * Control notification settings
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
     * Get validation rules using Laravel validation
     */
    protected function validationRules(): array
    {
        return [
            'name' => 'required|string|max:100',
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
            'name.required' => 'Customer name is required',
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
     * Custom manual validations for business logic
     */
    protected function customValidations(): void
    {
        $errors = [];

        // Manual CPF/CNPJ validation with business logic
        if (isset($this->data['cpfCnpj'])) {
            $cleanDocument = preg_replace('/\D/', '', $this->data['cpfCnpj']);

            // Check if CPF/CNPJ length is valid
            if (!in_array(strlen($cleanDocument), [11, 14])) {
                $errors['cpfCnpj'][] = 'CPF must have 11 digits or CNPJ must have 14 digits';
            }

            // Basic CPF validation (you can implement full algorithm)
            elseif (strlen($cleanDocument) === 11 && !$this->isValidCpf($cleanDocument)) {
                $errors['cpfCnpj'][] = 'Please provide a valid CPF number';
            }

            // Basic CNPJ validation (you can implement full algorithm)
            elseif (strlen($cleanDocument) === 14 && !$this->isValidCnpj($cleanDocument)) {
                $errors['cpfCnpj'][] = 'Please provide a valid CNPJ number';
            }
        }

        // Validate postal code format for Brazil
        if (isset($this->data['postalCode']) && isset($this->data['country']) && $this->data['country'] === 'BR') {
            $cleanPostalCode = preg_replace('/\D/', '', $this->data['postalCode']);
            if (strlen($cleanPostalCode) !== 8) {
                $errors['postalCode'][] = 'Brazilian postal code must have exactly 8 digits';
            }
        }

        // Business rule: If state is provided, country must be BR
        if (isset($this->data['state']) && (!isset($this->data['country']) || $this->data['country'] !== 'BR')) {
            $errors['country'][] = 'Country must be BR when Brazilian state is provided';
        }

        // Business rule: Email domain validation for corporate customers
        if (isset($this->data['email']) && isset($this->data['cpfCnpj'])) {
            $cleanDocument = preg_replace('/\D/', '', $this->data['cpfCnpj']);
            if (strlen($cleanDocument) === 14) { // CNPJ (company)
                $emailDomain = substr(strrchr($this->data['email'], '@'), 1);
                if (in_array($emailDomain, ['gmail.com', 'hotmail.com', 'yahoo.com'])) {
                    $errors['email'][] = 'Corporate customers should use business email addresses';
                }
            }
        }

        // If there are validation errors, throw them
        if (!empty($errors)) {
            $this->addValidationErrors($errors);
        }
    }

    /**
     * Basic CPF validation (simplified - implement full algorithm as needed)
     */
    private function isValidCpf(string $cpf): bool
    {
        // Reject known invalid patterns
        if (preg_match('/(\d)\1{10}/', $cpf)) {
            return false; // All digits are the same
        }

        // Here you would implement the full CPF validation algorithm
        // For now, just basic checks
        return true;
    }

    /**
     * Basic CNPJ validation (simplified - implement full algorithm as needed)
     */
    private function isValidCnpj(string $cnpj): bool
    {
        // Reject known invalid patterns
        if (preg_match('/(\d)\1{13}/', $cnpj)) {
            return false; // All digits are the same
        }

        // Here you would implement the full CNPJ validation algorithm
        // For now, just basic checks
        return true;
    }
}
