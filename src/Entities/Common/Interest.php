<?php

namespace Leopaulo88\Asaas\Entities\Common;

use Leopaulo88\Asaas\Entities\BaseEntity;

class Interest extends BaseEntity
{

    public function __construct(
        public ?float $value = null,
    )
    {
    }


    public function value(float $value): self
    {
        $this->value = $value;
        return $this;
    }
}