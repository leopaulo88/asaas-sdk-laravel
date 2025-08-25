<?php

namespace Leopaulo88\Asaas\Entities\Transfer;

use Carbon\Carbon;
use Leopaulo88\Asaas\Entities\BaseEntity;
use Leopaulo88\Asaas\Entities\Common\BankAccount;
use Leopaulo88\Asaas\Entities\Common\Recurring;

class TransferCreate extends BaseEntity
{
    public function __construct(
        public ?float $value = null,
        public ?string $walletId = null,
        public ?BankAccount $bankAccount = null,
        public ?string $operationType = null,
        public ?string $pixAddressKey = null,
        public ?string $pixAddressKeyType = null,
        public ?string $description = null,
        public ?Carbon $scheduleDate = null,
        public ?string $externalReference = null,
        public ?Recurring $recurring = null,
    ) {}

    public function value(float $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function walletId(string $walletId): self
    {
        $this->walletId = $walletId;

        return $this;
    }

    public function bankAccount(array|BankAccount $bankAccount): self
    {
        if (is_array($bankAccount)) {
            $bankAccount = BankAccount::fromArray($bankAccount);
        }

        $this->bankAccount = $bankAccount;

        return $this;
    }

    public function operationType(string $operationType): self
    {
        $this->operationType = $operationType;

        return $this;
    }

    public function pixAddressKey(string $pixAddressKey): self
    {
        $this->pixAddressKey = $pixAddressKey;

        return $this;
    }

    public function pixAddressKeyType(string $pixAddressKeyType): self
    {
        $this->pixAddressKeyType = $pixAddressKeyType;

        return $this;
    }

    public function description(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function scheduleDate(Carbon $scheduleDate): self
    {
        $this->scheduleDate = $scheduleDate;

        return $this;
    }

    public function externalReference(string $externalReference): self
    {
        $this->externalReference = $externalReference;

        return $this;
    }

    public function recurring(array|Recurring $recurring): self
    {
        if (is_array($recurring)) {
            $recurring = Recurring::fromArray($recurring);
        }

        $this->recurring = $recurring;

        return $this;
    }
}
