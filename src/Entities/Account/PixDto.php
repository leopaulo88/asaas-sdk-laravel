<?php

namespace Hubooai\Asaas\Entities\Account;

class PixDto
{
    public string $canReceivePix;
    public string $pixAccountType;

    public function __construct(array $data)
    {
        $this->canReceivePix = $data['canReceivePix'] ?? '';
        $this->pixAccountType = $data['pixAccountType'] ?? '';
    }

    public static function fromArray(array $data): self
    {
        return new self($data);
    }

    public function toArray(): array
    {
        return [
            'canReceivePix' => $this->canReceivePix,
            'pixAccountType' => $this->pixAccountType,
        ];
    }
}
