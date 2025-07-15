<?php

namespace Leopaulo88\Asaas\Contracts;

interface RequestBuilderInterface
{
    /**
     * Convert request to array for API submission
     */
    public function toArray(): array;
}
