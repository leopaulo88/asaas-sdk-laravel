<?php

namespace Leopaulo88\Asaas\Entities\Common;

use Carbon\Carbon;
use Leopaulo88\Asaas\Entities\BaseEntity;
use Leopaulo88\Asaas\Enums\BankAccountType;

class Bank extends BaseEntity
{
    public ?string $ispb;
    public ?string $name;

    public function __construct(
        public ?string $code = null,
    ) {}

    public function code(string $code): self
    {
        $this->code = $code;
        return $this;
    }
}
