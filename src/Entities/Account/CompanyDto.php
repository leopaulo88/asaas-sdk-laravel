<?php

namespace Hubooai\Asaas\Entities\Account;

class CompanyDto
{
    public string $companyName;
    public string $tradingName;
    public ?string $site;
    public string $incomeValue;

    public function __construct(array $data)
    {
        $this->companyName = $data['companyName'] ?? '';
        $this->tradingName = $data['tradingName'] ?? '';
        $this->site = $data['site'] ?? null;
        $this->incomeValue = $data['incomeValue'] ?? '';
    }

    public static function fromArray(array $data): self
    {
        return new self($data);
    }

    public function toArray(): array
    {
        return [
            'companyName' => $this->companyName,
            'tradingName' => $this->tradingName,
            'site' => $this->site,
            'incomeValue' => $this->incomeValue,
        ];
    }
}
