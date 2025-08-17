<?php

namespace Leopaulo88\Asaas\Entities\Account;

use Leopaulo88\Asaas\Entities\BaseResponse;
use Leopaulo88\Asaas\Entities\Common\AccountNumber;
use Leopaulo88\Asaas\Entities\Common\CommercialInfoExpiration;

class AccountResponse extends BaseResponse
{
    public ?string $object;

    public ?string $id;

    public ?string $name;

    public ?string $email;

    public ?string $loginEmail;

    public ?string $phone;

    public ?string $mobilePhone;

    public ?string $address;

    public ?string $addressNumber;

    public ?string $complement;

    public ?string $province;

    public ?string $postalCode;

    public ?string $cpfCnpj;

    public ?string $birthDate;

    public ?string $personType;

    public ?string $companyType;

    public ?string $city;

    public ?string $state;

    public ?string $country;

    public ?string $tradingName;

    public ?string $site;

    public ?string $walletId;

    public ?AccountNumber $accountNumber;

    public ?CommercialInfoExpiration $commercialInfoExpiration;

    public ?string $apiKey;
}
