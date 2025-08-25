<?php

namespace Leopaulo88\Asaas\Entities\Common;

use Leopaulo88\Asaas\Entities\BaseEntity;

class Recurring extends BaseEntity
{
    /**
     * Number of repetitions. This transfer will be included as the first transaction of the recurrence.
     * For the WEEKLY frequency, the maximum accepted is: 51
     * For the MONTHLY frequency, the maximum accepted is: 11
     */
    public function __construct(
        public ?string $frequency = null,
        public ?int $quantity = null,
    ) {}

    public function frequency(string $frequency): self
    {
        $this->frequency = $frequency;

        return $this;
    }

    public function quantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }
}
