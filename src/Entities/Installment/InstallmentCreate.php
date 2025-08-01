<?php

namespace Leopaulo88\Asaas\Entities\Installment;

use Carbon\Carbon;
use Leopaulo88\Asaas\Entities\BaseEntity;
use Leopaulo88\Asaas\Entities\Common\CreditCard;
use Leopaulo88\Asaas\Entities\Common\CreditCardHolderInfo;
use Leopaulo88\Asaas\Entities\Common\Discount;
use Leopaulo88\Asaas\Entities\Common\Fine;
use Leopaulo88\Asaas\Entities\Common\Interest;
use Leopaulo88\Asaas\Entities\Common\Split;
use Leopaulo88\Asaas\Enums\BillingType;

class InstallmentCreate extends BaseEntity
{
    public function __construct(
        public ?int $installmentCount = null,
        public ?string $customer = null,
        public ?float $value = null,
        public ?float $totalValue = null,
        public ?BillingType $billingType = null,
        public ?Carbon $dueDate = null,
        public ?string $description = null,
        public ?bool $postalService = null,
        public ?int $daysAfterDueDateToRegistrationCancellation = null,
        public ?string $externalReference = null,
        public ?Discount $discount = null,
        public ?Interest $interest = null,
        public ?Fine $fine = null,
        /** @var Split[]|null */
        public ?array $split = null,

        // Credit Card
        public ?CreditCard $creditCard = null,
        public ?CreditCardHolderInfo $creditCardHolderInfo = null,
        public ?string $creditCardToken = null,
        public ?bool $authorizeOnly = null,
        public ?string $remoteIp = null,
    ) {}

    public function installmentCount(int $installmentCount): self
    {
        $this->installmentCount = $installmentCount;

        return $this;
    }

    public function customer(string $customer): self
    {
        $this->customer = $customer;

        return $this;
    }

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

    public function totalValue(float $totalValue): self
    {
        $this->totalValue = $totalValue;

        return $this;
    }

    public function dueDate(string|Carbon $dueDate): self
    {
        if (is_string($dueDate)) {
            $dueDate = Carbon::parse($dueDate);
        }
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

    public function postalService(bool $postalService): self
    {
        $this->postalService = $postalService;

        return $this;
    }

    /**
     * @param  Split[]  $split
     */
    public function split(array $split): self
    {
        $splits = [];

        foreach ($split as $item) {
            if (is_array($item)) {
                $splits[] = Split::fromArray($item);
            } else {
                $splits[] = $item;
            }
        }

        $this->split = $splits;

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
