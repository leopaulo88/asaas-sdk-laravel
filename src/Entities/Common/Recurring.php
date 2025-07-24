<?php

namespace Leopaulo88\Asaas\Entities\Common;

use Leopaulo88\Asaas\Entities\BaseEntity;
use Leopaulo88\Asaas\Enums\RecurringFrequency;

class Recurring extends BaseEntity
{
    /**
     * Number of repetitions. This transfer will be included as the first transaction of the recurrence.
     * For the WEEKLY frequency, the maximum accepted is: 51
     * For the MONTHLY frequency, the maximum accepted is: 11
     */
    public function __construct(
        public ?RecurringFrequency $frequency = null,
        public ?int $quantity = null,
    ) {}

    public function frequency(string|RecurringFrequency $frequency): self
    {
        if (is_string($frequency)) {
            $frequency = RecurringFrequency::tryFrom($frequency);
        }

        $this->frequency = $frequency;

        return $this;
    }

    public function quantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }
}
