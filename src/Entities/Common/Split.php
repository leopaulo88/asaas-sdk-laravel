<?php

namespace Leopaulo88\Asaas\Entities\Common;

use Leopaulo88\Asaas\Entities\BaseEntity;

class Split extends BaseEntity
{
    public function __construct(
        public ?string $id = null,
        public ?string $walletId = null,
        public ?float $fixedValue = null,
        public ?float $percentualValue = null,
        public ?float $totalFixedValue = null,
        public ?float $totalValue = null,
        public ?string $cancellationReason = null,
        public ?string $status = null,
        public ?string $externalReference = null,
        public ?string $description = null
    ) {}

    public function walletId(string $walletId): self
    {
        $this->walletId = $walletId;

        return $this;
    }

    public function fixedValue(float $fixedValue): self
    {
        $this->fixedValue = $fixedValue;

        return $this;
    }

    public function percentualValue(float $percentualValue): self
    {
        $this->percentualValue = $percentualValue;

        return $this;
    }

    public function totalFixedValue(float $totalFixedValue): self
    {
        $this->totalFixedValue = $totalFixedValue;

        return $this;
    }

    public function externalReference(string $externalReference): self
    {
        $this->externalReference = $externalReference;

        return $this;
    }

    public function description(string $description): self
    {
        $this->description = $description;

        return $this;
    }
}
