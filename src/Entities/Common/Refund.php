<?php

namespace Leopaulo88\Asaas\Entities\Common;

use Carbon\Carbon;
use Leopaulo88\Asaas\Entities\BaseEntity;
use Leopaulo88\Asaas\Enums\RefundsStatus;

/**
 * @var RefundedSplit[] $refundedSplits
 */
class Refund extends BaseEntity
{
    // Only for response
    public ?Carbon $dateCreated;

    public ?RefundsStatus $status;

    public ?string $endToEndIdentifier;

    public ?Carbon $effectiveDate;

    public ?string $transactionReceiptUrl;

    public ?array $refundedSplits;

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
