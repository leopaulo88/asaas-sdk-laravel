<?php

namespace Hubooai\Asaas\Entities\Account;

class BankAccountDto
{
    public string $accountNumber;
    public string $agency;
    public string $accountDigit;
    public string $walletId;

    public function __construct(array $data)
    {
        $this->accountNumber = $data['accountNumber'] ?? '';
        $this->agency = $data['agency'] ?? '';
        $this->accountDigit = $data['accountDigit'] ?? '';
        $this->walletId = $data['walletId'] ?? '';
    }

    public static function fromArray(array $data): self
    {
        return new self($data);
    }

    public function toArray(): array
    {
        return [
            'accountNumber' => $this->accountNumber,
            'agency' => $this->agency,
            'accountDigit' => $this->accountDigit,
            'walletId' => $this->walletId,
        ];
    }
}
