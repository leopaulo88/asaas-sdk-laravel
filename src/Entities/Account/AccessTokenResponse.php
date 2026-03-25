<?php

namespace Leopaulo88\Asaas\Entities\Account;

use Carbon\Carbon;
use Leopaulo88\Asaas\Entities\BaseEntity;

class AccessTokenResponse extends BaseEntity
{
    public ?string $id;

    public ?string $name;

    public ?bool $enabled;

    public ?Carbon $expirationDate;

    public ?Carbon $dateCreated;

    public ?Carbon $projectedExpirationDateByLackOfUse;

    public ?string $apiKey;
}
