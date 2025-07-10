<?php

namespace Hubooai\Asaas\Entities\Account;

class AccountNumberDto
{
    public string $agency;
    public string $account;
    public string $accountDigit;

    public function __construct(array $data = [])
    {
        $this->agency = $data['agency'] ?? '';
        $this->account = $data['account'] ?? '';
        $this->accountDigit = $data['accountDigit'] ?? '';
    }

    public static function fromArray(array $data): self
    {
        return new self($data);
    }

    public function toArray(): array
    {
        return [
            'agency' => $this->agency,
            'account' => $this->account,
            'accountDigit' => $this->accountDigit,
        ];
    }
}
