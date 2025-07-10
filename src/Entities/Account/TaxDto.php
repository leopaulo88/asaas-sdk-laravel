<?php

namespace Hubooai\Asaas\Entities\Account;

class TaxDto
{
    public string $municipalInscription;
    public string $stateInscription;

    public function __construct(array $data)
    {
        $this->municipalInscription = $data['municipalInscription'] ?? '';
        $this->stateInscription = $data['stateInscription'] ?? '';
    }

    public static function fromArray(array $data): self
    {
        return new self($data);
    }

    public function toArray(): array
    {
        return [
            'municipalInscription' => $this->municipalInscription,
            'stateInscription' => $this->stateInscription,
        ];
    }
}
