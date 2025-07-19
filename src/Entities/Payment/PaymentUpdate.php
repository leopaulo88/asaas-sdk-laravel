<?php

namespace Leopaulo88\Asaas\Entities\Payment;

use Leopaulo88\Asaas\Entities\BaseEntity;
use Leopaulo88\Asaas\Entities\Common\Callback;
use Leopaulo88\Asaas\Entities\Common\CreditCard;
use Leopaulo88\Asaas\Entities\Common\CreditCardHolderInfo;
use Leopaulo88\Asaas\Entities\Common\Discount;
use Leopaulo88\Asaas\Entities\Common\Fine;
use Leopaulo88\Asaas\Entities\Common\Interest;
use Leopaulo88\Asaas\Entities\Common\Split;
use Leopaulo88\Asaas\Enums\BillingType;

/** @var Split[] $split */
class PaymentUpdate extends BaseEntity
{
    public function __construct(
        public ?BillingType $billingType = null,
        public ?float $value = null,
        public ?string $dueDate = null,
        public ?string $description = null,
        public ?int $daysAfterDueDateToRegistrationCancellation = null,
        public ?string $externalReference = null,
        public ?Discount $discount = null,
        public ?Interest $interest = null,
        public ?Fine $fine = null,
        public ?bool $postalService = null,
        public ?array $split = null,
        public ?Callback $callback = null,
    ) {}

    public function billingType(string|BillingType $billingType): self
    {
        if (is_string($billingType)) {
            $billingType = BillingType::tryFrom($billingType);
        }

        $this->billingType = $billingType;
        return $this;
    }

    public function value(float $value): self
    {
        $this->value = $value;
        return $this;
    }

    public function dueDate(string $dueDate): self
    {
        $this->dueDate = $dueDate;
        return $this;
    }

    public function description(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function daysAfterDueDateToRegistrationCancellation(int $days): self
    {
        $this->daysAfterDueDateToRegistrationCancellation = $days;
        return $this;
    }

    public function externalReference(string $externalReference): self
    {
        $this->externalReference = $externalReference;
        return $this;
    }

    public function discount(array|Discount $discount): self
    {
        if(is_array($discount)) {
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

    public function postalService(bool $postalService): self
    {
        $this->postalService = $postalService;
        return $this;
    }

    /**
     * @param Split[] $split
     */
    public function split(array $split): self
    {
        $splits = [];

        foreach ($split as $item) {
            if ($item instanceof Split) {
                $splits[] = $item;
            } elseif (is_array($item)) {
                $splits[] = Split::fromArray($item);
            }
        }

        $this->split = $splits;
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
}
