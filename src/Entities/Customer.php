<?php

namespace Leopaulo88\AsaasSdkLaravel\Entities;

use Leopaulo88\AsaasSdkLaravel\Entities\Customer\CustomerCreateRequest;
use Leopaulo88\AsaasSdkLaravel\Entities\Customer\CustomerUpdateRequest;

class Customer
{
    /**
     * Create a new customer create request
     */
    public static function create(array $data = []): CustomerCreateRequest
    {
        return CustomerCreateRequest::create($data);
    }

    /**
     * Create a new customer update request
     */
    public static function update(array $data = []): CustomerUpdateRequest
    {
        return CustomerUpdateRequest::create($data);
    }
}
