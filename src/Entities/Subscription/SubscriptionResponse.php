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
    public ?string $object = null;

    public ?string $id = null;

    public ?Carbon $dateCreated = null;

    public ?string $customer = null;

    public ?string $paymentLink = null;

    public ?string $billingType = null;

    public ?string $cycle = null;

    public ?float $value = null;

    public ?Carbon $nextDueDate = null;

    public ?Carbon $endDate = null;

    public ?string $description = null;

    public ?string $status = null;

    public ?Discount $discount = null;

    public ?Fine $fine = null;

    public ?Interest $interest = null;

    public ?bool $deleted = null;

    public ?int $maxPayments = null;

    public ?string $externalReference = null;

    public ?string $checkoutSession = null;

    /** @var Split[]|null */
    public ?array $split = null;
}
