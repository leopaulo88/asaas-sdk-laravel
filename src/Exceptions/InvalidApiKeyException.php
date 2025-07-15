<?php

namespace Leopaulo88\Asaas\Exceptions;

class InvalidApiKeyException extends AsaasException
{
    public function __construct(string $message = 'API key is not configured or invalid')
    {
        parent::__construct($message);
    }
}
