<?php

namespace Leopaulo88\Asaas\Entities;

use Leopaulo88\Asaas\Entities\Customer\CustomerCreateRequest;
use Leopaulo88\Asaas\Entities\Customer\CustomerUpdateRequest;

class Customer
{
    /**
     * Create a new customer create request
     */
    public static function create(array $data = []): CustomerCreateRequest
    {
        return new CustomerCreateRequest($data);
    }

    /**
     * Create a new customer update request
     */
    public static function update(array $data = []): CustomerUpdateRequest
    {
        return new CustomerUpdateRequest($data);
    }
}
