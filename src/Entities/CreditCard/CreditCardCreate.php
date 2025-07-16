<?php

namespace Leopaulo88\Asaas\Entities\CreditCard;

use Leopaulo88\Asaas\Concerns\ValidatesData;
use Leopaulo88\Asaas\Entities\BaseEntity;
use Leopaulo88\Asaas\Entities\Common\CreditCard;
use Leopaulo88\Asaas\Entities\Common\CreditCardHolderInfo;

class CreditCardCreate extends BaseEntity
{
    use ValidatesData;

    public function __construct(
        public ?string $customer = null,
        public ?CreditCard $creditCard = null,
        public ?CreditCardHolderInfo $creditCardHolderInfo = null,
        public ?string $remoteIp = null
    ) {}

    // metodos fluentes para definir os valores
    public function customer(string $customer): self
    {
        $this->customer = $customer;

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

    public function remoteIp(string $remoteIp): self
    {
        $this->remoteIp = $remoteIp;

        return $this;
    }

    protected function validationRules(): array
    {
        return [
            'customer' => 'required',
            'creditCard' => 'required',
            'creditCardHolderInfo' => 'required',
            'remoteIp' => 'required',
        ];
    }
}
