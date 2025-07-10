<?php

namespace Hubooai\Asaas\Exceptions;

class AccountException extends AsaasException
{
    public static function invalidData(array $errors): self
    {
        return new self('Invalid account data provided', 400, $errors);
    }

    public static function notFound(string $accountId): self
    {
        return new self("Account with ID {$accountId} not found", 404);
    }

    public static function creationFailed(string $message, array $errors = []): self
    {
        return new self("Account creation failed: {$message}", 422, $errors);
    }

    public static function updateFailed(string $accountId, string $message, array $errors = []): self
    {
        return new self("Failed to update account {$accountId}: {$message}", 422, $errors);
    }
}
