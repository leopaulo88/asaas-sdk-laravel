<?php

namespace Leopaulo88\Asaas\Entities\Common;

use Carbon\Carbon;
use Leopaulo88\Asaas\Entities\BaseEntity;

class Refund extends BaseEntity
{
    public ?Carbon $dateCreated = null;

    public ?string $status = null;

    public ?string $endToEndIdentifier = null;

    public ?Carbon $effectiveDate = null;

    public ?string $transactionReceiptUrl = null;

    /** @var RefundedSplit[]|null */
    public ?array $refundedSplits = null;

    public ?string $paymentId = null;

    public function __construct(
        public ?float $value = null,
        public ?string $description = null,
    ) {}

    public function value(float $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function description(string $description): self
    {
        $this->description = $description;

        return $this;
    }
}
