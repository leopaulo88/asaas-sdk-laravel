<?php

namespace Leopaulo88\Asaas\Entities\Common;

use Leopaulo88\Asaas\Entities\BaseEntity;
use Leopaulo88\Asaas\Enums\FineType;

class Fine extends BaseEntity
{
    public function __construct(
        public ?float $value = null,
        public ?FineType $type = null
    ) {}

    public function value(float $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function type(string|FineType $type): self
    {
        if (is_string($type)) {
            $type = FineType::tryFrom($type);
        }

        $this->type = $type;

        return $this;
    }
}
