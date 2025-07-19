<?php

namespace Leopaulo88\Asaas\Entities\Account;

use Carbon\Carbon;
use Leopaulo88\Asaas\Entities\BaseResponse;

class AccountResponse extends BaseResponse
{
    // Basic info
    public ?string $object;

    public ?string $id;

    public ?Carbon $dateCreated;

    public ?string $name;

    public ?string $email;

    public ?string $loginEmail;

    public ?string $phone;

    public ?string $mobilePhone;

    // Address information
    public ?string $address;

    public ?string $addressNumber;

    public ?string $complement;

    public ?string $province;

    public ?string $city;

    public ?string $state;

    public ?string $country;

    public ?string $postalCode;

    // Document and identification
    public ?string $cpfCnpj;

    public ?string $birthDate;

    public ?string $personType;

    public ?string $companyType;

    // Account specific fields
    public ?string $site;

    public ?array $walletId;

    public ?string $apiKey;
}
