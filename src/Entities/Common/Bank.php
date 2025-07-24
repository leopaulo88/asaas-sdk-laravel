<?php

namespace Leopaulo88\Asaas\Entities\Common;

use Leopaulo88\Asaas\Entities\BaseEntity;

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
