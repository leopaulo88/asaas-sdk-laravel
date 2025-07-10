<?php

namespace Hubooai\Asaas\Exceptions;

use Exception;

class AsaasException extends Exception
{
    public function __construct(string $message = '', int $code = 0, ?Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
