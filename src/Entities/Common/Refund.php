<?php

namespace Leopaulo88\Asaas\Entities\Common;

use Carbon\Carbon;
use Leopaulo88\Asaas\Entities\BaseEntity;

class Refund extends BaseEntity
{
    public ?Carbon $dateCreated;

    public ?string $status;

    public ?string $endToEndIdentifier;

    public ?Carbon $effectiveDate;

    public ?string $transactionReceiptUrl;

    /** @var RefundedSplit[]|null */
    public ?array $refundedSplits;

    public ?string $paymentId;

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
