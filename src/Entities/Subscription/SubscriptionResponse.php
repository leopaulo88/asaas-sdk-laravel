<?php

namespace Leopaulo88\Asaas\Entities\Subscription;

use Carbon\Carbon;
use Leopaulo88\Asaas\Entities\BaseResponse;
use Leopaulo88\Asaas\Entities\Common\Discount;
use Leopaulo88\Asaas\Entities\Common\Fine;
use Leopaulo88\Asaas\Entities\Common\Interest;
use Leopaulo88\Asaas\Entities\Common\Split;

class SubscriptionResponse extends BaseResponse
{
    public ?string $object;

    public ?string $id;

    public ?Carbon $dateCreated;

    public ?string $customer;

    public ?string $paymentLink;

    public ?string $billingType;

    public ?string $cycle;

    public ?float $value;

    public ?Carbon $nextDueDate;

    public ?Carbon $endDate;

    public ?string $description;

    public ?string $status;

    public ?Discount $discount;

    public ?Fine $fine;

    public ?Interest $interest;

    public ?bool $deleted;

    public ?int $maxPayments;

    public ?string $externalReference;

    public ?string $checkoutSession;

    /** @var Split[]|null */
    public ?array $split = null;
}
