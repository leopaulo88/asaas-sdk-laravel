<?php

namespace Leopaulo88\Asaas\Entities\Common;

use Carbon\Carbon;
use Leopaulo88\Asaas\Entities\BaseEntity;
use Leopaulo88\Asaas\Enums\BankAccountType;

class BankAccount extends BaseEntity
{
    public ?string $agencyDigit;
    public ?string $pixAddressKey;

    public function __construct(
        public ?Bank $bank = null,
        public ?string $accountName = null,
        public ?string $ownerName = null,
        public ?Carbon $ownerBirthDate = null,
        public ?string $cpfCnpj = null,
        public ?string $agency = null,
        public ?string $account = null,
        public ?string $accountDigit = null,
        public ?BankAccountType $bankAccountType = null,
        public ?string $ispb = null,
    ) {}

    public function bank(array|Bank $bank): self
    {
        if (is_array($bank)) {
            $bank = Bank::fromArray($bank);
        }

        $this->bank = $bank;
        return $this;
    }

    public function accountName(string $accountName): self
    {
        $this->accountName = $accountName;
        return $this;
    }

    public function ownerName(string $ownerName): self
    {
        $this->ownerName = $ownerName;
        return $this;
    }

    public function ownerBirthDate(Carbon $ownerBirthDate): self
    {
        $this->ownerBirthDate = $ownerBirthDate;
        return $this;
    }

    public function cpfCnpj(string $cpfCnpj): self
    {
        $this->cpfCnpj = $cpfCnpj;
        return $this;
    }

    public function agency(string $agency): self
    {
        $this->agency = $agency;
        return $this;
    }

    public function account(string $account): self
    {
        $this->account = $account;
        return $this;
    }

    public function accountDigit(string $accountDigit): self
    {
        $this->accountDigit = $accountDigit;
        return $this;
    }

    public function bankAccountType(BankAccountType $bankAccountType): self
    {
        $this->bankAccountType = $bankAccountType;
        return $this;
    }

    public function ispb(string $ispb): self
    {
        $this->ispb = $ispb;
        return $this;
    }
}
