<?php

namespace Leopaulo88\Asaas\Entities\Customer;

use Carbon\Carbon;
use Leopaulo88\Asaas\Entities\BaseResponse;

class CustomerResponse extends BaseResponse
{
    // Basic info
    public ?string $object = null;

    public ?string $id = null;

    public ?Carbon $dateCreated = null;

    public ?string $name = null;

    public ?string $email = null;

    public ?string $phone = null;

    public ?string $mobilePhone = null;

    // Address information
    public ?string $address = null;

    public ?string $addressNumber = null;

    public ?string $complement = null;

    public ?string $province = null;

    public ?int $city = null;

    public ?string $cityName = null;

    public ?string $state = null;

    public ?string $country = null;

    public ?string $postalCode = null;

    public ?string $cpfCnpj = null;

    public ?string $personType = null;

    public ?bool $deleted = null;

    public ?string $additionalEmails = null;

    public ?string $externalReference = null;

    public ?bool $notificationDisabled = null;

    public ?string $observations = null;

    public ?bool $foreignCustomer = null;
}
