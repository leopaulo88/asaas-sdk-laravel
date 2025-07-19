<?php

namespace Leopaulo88\Asaas\Entities\Customer;

use Carbon\Carbon;
use Leopaulo88\Asaas\Entities\BaseResponse;
use Leopaulo88\Asaas\Enums\PersonType;

class CustomerResponse extends BaseResponse
{
    // Basic info
    public ?string $object;

    public ?string $id;

    public ?Carbon $dateCreated;

    public ?string $name;

    public ?string $email;

    public ?string $phone;

    public ?string $mobilePhone;

    // Address information
    public ?string $address;

    public ?string $addressNumber;

    public ?string $complement;

    public ?string $province;

    public ?int $city;

    public ?string $cityName;

    public ?string $state;

    public ?string $country;

    public ?string $postalCode;

    // Document and identification
    public ?string $cpfCnpj;

    public ?PersonType $personType;

    // Additional fields
    public ?bool $deleted;

    public ?string $additionalEmails;

    public ?string $externalReference;

    public ?bool $notificationDisabled;

    public ?string $observations;

    public ?bool $foreignCustomer;
}
