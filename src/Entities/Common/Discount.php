<?php

namespace Leopaulo88\Asaas\Entities\Common;

use Leopaulo88\Asaas\Entities\BaseEntity;
use Leopaulo88\Asaas\Enums\DiscountType;

class Discount extends BaseEntity
{
    public function __construct(
        public ?float $value = null,
        public ?int $dueDateLimitDays = null,
        public ?DiscountType $type = null
    ) {}

    public function value(float $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function dueDateLimitDays(int $dueDateLimitDays): self
    {
        $this->dueDateLimitDays = $dueDateLimitDays;

        return $this;
    }

    public function type(string|DiscountType $type): self
    {
        if (is_string($type)) {
            $type = DiscountType::tryFrom($type);
        }

        $this->type = $type;

        return $this;
    }
}
