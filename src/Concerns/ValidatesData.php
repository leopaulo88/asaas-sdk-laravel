<?php

namespace Leopaulo88\AsaasSdkLaravel\Concerns;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

trait ValidatesData
{
    /**
     * Validate data using Laravel validation rules
     */
    protected function validateData(array $data, array $rules, array $messages = []): void
    {
        $validator = Validator::make($data, $rules, $messages);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }

    /**
     * Get validation rules for this entity
     */
    abstract protected function validationRules(): array;

    /**
     * Get custom validation messages
     */
    protected function validationMessages(): array
    {
        return [];
    }

    /**
     * Perform custom manual validations
     * Override this method to add business logic validations
     */
    protected function customValidations(): void
    {
        // Override in child classes for custom validations
    }

    /**
     * Validate current data (both Laravel rules and custom validations)
     */
    protected function validate(): void
    {
        // First run Laravel validation rules
        $this->validateData($this->data, $this->validationRules(), $this->validationMessages());

        // Then run custom manual validations
        $this->customValidations();
    }

    /**
     * Helper method to throw validation errors with specific field
     */
    protected function addValidationError(string $field, string $message): void
    {
        $validator = Validator::make([], []);
        $validator->errors()->add($field, $message);
        throw new ValidationException($validator);
    }

    /**
     * Helper method to add multiple validation errors
     */
    protected function addValidationErrors(array $errors): void
    {
        $validator = Validator::make([], []);

        foreach ($errors as $field => $messages) {
            if (is_array($messages)) {
                foreach ($messages as $message) {
                    $validator->errors()->add($field, $message);
                }
            } else {
                $validator->errors()->add($field, $messages);
            }
        }

        throw new ValidationException($validator);
    }
}
