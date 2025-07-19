<?php

namespace Leopaulo88\Asaas\Exceptions;

use Throwable;

class InvalidEnvironmentException extends AsaasException
{
    public function __construct(string $environment, array $availableEnvironments = [], int $code = 400, ?Throwable $previous = null, ?array $data = [])
    {
        $available = implode(', ', $availableEnvironments);
        $message = "Invalid environment '{$environment}'. Available environments: {$available}";

        parent::__construct($message, $code, $previous, $data);
    }
}
