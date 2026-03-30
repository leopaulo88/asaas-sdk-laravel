<?php

namespace Leopaulo88\Asaas\Entities\Account;

use Carbon\Carbon;
use Leopaulo88\Asaas\Entities\BaseEntity;

class AccessTokenUpdate extends BaseEntity
{
    public function __construct(
        public ?string $name = null,
        public ?bool $enabled = null,
        public ?Carbon $expirationDate = null

    ) {}

    public function name(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function enabled(bool $enabled): self
    {
        $this->enabled = $enabled;

        return $this;
    }

    public function expirationDate(Carbon $expirationDate): self
    {
        $this->expirationDate = $expirationDate;

        return $this;
    }
}
