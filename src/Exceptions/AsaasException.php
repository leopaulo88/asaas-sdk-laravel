<?php

namespace Hubooai\Asaas\Exceptions;

class AsaasException extends \Exception
{
    protected array $errors;

    public function __construct(string $message = '', int $code = 0, array $errors = [], \Throwable $previous = null)
    {
        $this->errors = $errors;
        parent::__construct($message, $code, $previous);
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
