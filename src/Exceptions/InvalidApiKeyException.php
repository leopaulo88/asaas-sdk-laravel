<?php

namespace Leopaulo88\Asaas\Exceptions;

class InvalidApiKeyException extends AsaasException
{
    public function __construct(string $message = 'API key is not configured or invalid', int $code = 400, ?Throwable $previous = null, ?array $data = [])
    {
        parent::__construct($message, $code, $previous, $data);
    }
}
