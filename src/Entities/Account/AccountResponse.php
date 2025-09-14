<?php

namespace Leopaulo88\Asaas\Entities\Account;

use Leopaulo88\Asaas\Entities\BaseResponse;
use Leopaulo88\Asaas\Entities\Common\AccountNumber;
use Leopaulo88\Asaas\Entities\Common\CommercialInfoExpiration;

class AccountResponse extends BaseResponse
{
    public ?string $object = null;

    public ?string $id = null;

    public ?string $name = null;

    public ?string $email = null;

    public ?string $loginEmail = null;

    public ?string $phone = null;

    public ?string $mobilePhone = null;

    public ?string $address = null;

    public ?string $addressNumber = null;

    public ?string $complement = null;

    public ?string $province = null;

    public ?string $postalCode = null;

    public ?string $cpfCnpj = null;

    public ?string $birthDate = null;

    public ?string $personType = null;

    public ?string $companyType = null;

    public ?string $city = null;

    public ?string $state = null;

    public ?string $country = null;

    public ?string $tradingName = null;

    public ?string $site = null;

    public ?string $walletId = null;

    public ?AccountNumber $accountNumber = null;

    public ?CommercialInfoExpiration $commercialInfoExpiration = null;

    public ?string $apiKey = null;
}
