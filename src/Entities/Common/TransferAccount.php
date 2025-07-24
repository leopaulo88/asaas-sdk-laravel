<?php

namespace Leopaulo88\Asaas\Entities\Common;

use Leopaulo88\Asaas\Entities\BaseEntity;

class TransferAccount extends BaseEntity
{
    public ?string $name = null;
    public ?string $cpfCnpj = null;
    public ?string $agency = null;
    public ?string $account = null;
    public ?string $accountDigit = null;
}
