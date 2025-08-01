<?php

namespace Leopaulo88\Asaas\Entities\Subscription;

use Carbon\Carbon;
use Leopaulo88\Asaas\Entities\BaseEntity;
use Leopaulo88\Asaas\Entities\Common\Callback;
use Leopaulo88\Asaas\Entities\Common\Discount;
use Leopaulo88\Asaas\Entities\Common\Fine;
use Leopaulo88\Asaas\Entities\Common\Interest;
use Leopaulo88\Asaas\Entities\Common\Split;
use Leopaulo88\Asaas\Enums\BillingType;
use Leopaulo88\Asaas\Enums\SubscriptionCycle;
use Leopaulo88\Asaas\Enums\SubscriptionStatus;

class SubscriptionUpdate extends BaseEntity
{
    public function __construct(
        public ?BillingType $billingType = null,
        public ?SubscriptionStatus $status = null,
        public ?Carbon $nextDueDate = null,
        public ?Discount $discount = null,
        public ?Interest $interest = null,
        public ?Fine $fine = null,
        public ?SubscriptionCycle $cycle = null,
        public ?string $description = null,
        public ?Carbon $endDate = null,
        public ?bool $updatePendingPayments = null,
        public ?string $externalReference = null,
        /** @var Split[]|null */
        public ?array $split = null,
        public ?Callback $callback = null,
    ) {}

    public function billingType(BillingType $billingType): self
    {
        $this->billingType = $billingType;

        return $this;
    }

    public function nextDueDate(Carbon $nextDueDate): self
    {
        $this->nextDueDate = $nextDueDate;

        return $this;
    }

    public function discount(array|Discount $discount): self
    {
        if (is_array($discount)) {
            $discount = Discount::fromArray($discount);
        }

        $this->discount = $discount;

        return $this;
    }

    public function interest(array|Interest $interest): self
    {
        if (is_array($interest)) {
            $interest = Interest::fromArray($interest);
        }

        $this->interest = $interest;

        return $this;
    }

    public function fine(array|Fine $fine): self
    {
        if (is_array($fine)) {
            $fine = Fine::fromArray($fine);
        }

        $this->fine = $fine;

        return $this;
    }

    public function cycle(SubscriptionCycle $cycle): self
    {
        $this->cycle = $cycle;

        return $this;
    }

    public function description(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function endDate(Carbon $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function externalReference(string $externalReference): self
    {
        $this->externalReference = $externalReference;

        return $this;
    }

    /**
     * @param  Split[]|Split  $splits
     */
    public function split(array|Split $splits): self
    {
        $arraySplits = [];

        if ($splits instanceof Split) {
            $arraySplits[] = $splits;
        } else {
            foreach ($splits as $split) {
                if (is_array($split)) {
                    $arraySplits[] = Split::fromArray($split);
                } else {
                    $arraySplits[] = $split;
                }
            }
        }

        $this->split = $arraySplits;

        return $this;
    }

    public function callback(array|Callback $callback): self
    {
        if (is_array($callback)) {
            $callback = Callback::fromArray($callback);
        }

        $this->callback = $callback;

        return $this;
    }

    public function status(SubscriptionStatus $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function updatePendingPayments(bool $updatePendingPayments): self
    {
        $this->updatePendingPayments = $updatePendingPayments;

        return $this;
    }
}
