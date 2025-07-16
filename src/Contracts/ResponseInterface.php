<?php

namespace Leopaulo88\Asaas\Contracts;

use Illuminate\Http\Client\Response;

interface ResponseInterface
{
    /**
     * Create entity instance from array data
     */
    public static function fromArray(array $data): static;

    /**
     * Create entity instance from HTTP response
     */
    public static function fromResponse(Response $response): static;
}
