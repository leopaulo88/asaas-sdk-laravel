<?php

namespace Leopaulo88\Asaas\Entities\Payment;

use Leopaulo88\Asaas\Entities\BaseEntity;
use Leopaulo88\Asaas\Entities\Common\CreditCard;
use Leopaulo88\Asaas\Entities\Common\CreditCardHolderInfo;

class PaymentCreditCard extends BaseEntity
{
    public function __construct(
        public ?CreditCard $creditCard = null,
        public ?CreditCardHolderInfo $creditCardHolderInfo = null,
        public ?string $creditCardToken = null
    ) {}

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
}
