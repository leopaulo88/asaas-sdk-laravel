<?php

namespace Leopaulo88\Asaas\Entities\Subscription;

use Carbon\Carbon;
use Leopaulo88\Asaas\Entities\BaseEntity;
use Leopaulo88\Asaas\Entities\Common\CreditCard;
use Leopaulo88\Asaas\Entities\Common\CreditCardHolderInfo;
use Leopaulo88\Asaas\Entities\Common\Split;
use Leopaulo88\Asaas\Entities\Common\Callback;
use Leopaulo88\Asaas\Entities\Common\Discount;
use Leopaulo88\Asaas\Entities\Common\Fine;
use Leopaulo88\Asaas\Entities\Common\Interest;
use Leopaulo88\Asaas\Enums\BillingType;
use Leopaulo88\Asaas\Enums\SubscriptionCycle;

/** @var Split[] $split */
class SubscriptionCreate extends BaseEntity
{
    public function __construct(
        public ?string $customer = null,
        public ?BillingType $billingType = null,
        public ?float $value = null,
        public ?Carbon $nextDueDate = null,
        public ?Discount $discount = null,
        public ?Interest $interest = null,
        public ?Fine $fine = null,
        public ?SubscriptionCycle $cycle = null,
        public ?string $description = null,
        public ?Carbon $endDate = null,
        public ?int $maxPayments = null,
        public ?string $externalReference = null,
        public ?array $split = null,
        public ?Callback $callback = null,

        // Credit Card
        public ?CreditCard $creditCard = null,
        public ?CreditCardHolderInfo $creditCardHolderInfo = null,
        public ?string $creditCardToken = null,
        public ?bool $authorizeOnly = null,
        public ?string $remoteIp = null,
    ) {}

    public function customer(string $customer): self
    {
        $this->customer = $customer;

        return $this;
    }

    public function billingType(BillingType $billingType): self
    {
        $this->billingType = $billingType;

        return $this;
    }

    public function value(float $value): self
    {
        $this->value = $value;

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

    public function maxPayments(int $maxPayments): self
    {
        $this->maxPayments = $maxPayments;

        return $this;
    }

    public function externalReference(string $externalReference): self
    {
        $this->externalReference = $externalReference;

        return $this;
    }

    /** @var Split[]|Split */
    public function split(array|Split $splits): self
    {
        $arraySplits = [];

        if ($splits instanceof Split) {
            $arraySplits[] = Split::fromArray($splits);
        } else {
            foreach ($splits as $split) {
                if ($split instanceof Split) {
                    $arraySplits[] = Split::fromArray($split);
                } elseif (is_array($split)) {
                    $arraySplits[] = Split::fromArray($split);
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

    public function creditCard(array|CreditCard $creditCard): self
    {
        if (is_array($creditCard)) {
            $creditCard = CreditCard::fromArray($creditCard);
        }

        $this->creditCard = $creditCard;

        return $this;
    }

    public function creditCardHolderInfo(array|CreditCardHolderInfo $creditCardHolderInfo): self
    {
        if (is_array($creditCardHolderInfo)) {
            $creditCardHolderInfo = CreditCardHolderInfo::fromArray($creditCardHolderInfo);
        }
        $this->creditCardHolderInfo = $creditCardHolderInfo;

        return $this;
    }

    public function creditCardToken(string $creditCardToken): self
    {
        $this->creditCardToken = $creditCardToken;

        return $this;
    }

    public function authorizeOnly(bool $authorizeOnly): self
    {
        $this->authorizeOnly = $authorizeOnly;

        return $this;
    }

    public function remoteIp(string $remoteIp): self
    {
        $this->remoteIp = $remoteIp;

        return $this;
    }
}
