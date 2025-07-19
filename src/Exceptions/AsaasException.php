<?php

namespace Leopaulo88\Asaas\Exceptions;

use Exception;
use Throwable;

class AsaasException extends Exception
{
    protected array $data;

    public function __construct(string $message = '', int $code = 0, ?Throwable $previous = null, ?array $data = [])
    {
        parent::__construct($message, $code, $previous);

        $this->data = $data;
    }

    public function getData(): array
    {
        return $this->data;
    }
}
