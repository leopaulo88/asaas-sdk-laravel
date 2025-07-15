<?php

namespace Leopaulo88\Asaas\Exceptions;

class InvalidEnvironmentException extends AsaasException
{
    public function __construct(string $environment, array $availableEnvironments = [])
    {
        $available = implode(', ', $availableEnvironments);
        $message = "Invalid environment '{$environment}'. Available environments: {$available}";

        parent::__construct($message);
    }
}
