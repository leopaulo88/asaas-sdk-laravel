<?php

namespace Leopaulo88\Asaas\Entities\Common;

use Leopaulo88\Asaas\Entities\BaseEntity;

class AccountNumber extends BaseEntity
{
    public function __construct(
        public ?string $agency = null,
        public ?string $account = null,
        public ?string $accountDigit = null,
    ) {}
}
