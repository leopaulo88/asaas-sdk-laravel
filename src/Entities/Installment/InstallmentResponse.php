<?php

namespace Leopaulo88\Asaas\Entities\Installment;

use Carbon\Carbon;
use Leopaulo88\Asaas\Entities\BaseResponse;
use Leopaulo88\Asaas\Entities\Common\Chargeback;
use Leopaulo88\Asaas\Entities\Common\Refund;
use Leopaulo88\Asaas\Entities\CreditCardToken\CreditCardTokenResponse;

class InstallmentResponse extends BaseResponse
{
    public ?string $object;

    public ?string $id;

    public ?float $value;

    public ?float $netValue;

    public ?float $paymentValue;

    public ?int $installmentCount;

    public ?string $billingType;

    public ?Carbon $paymentDate;

    public ?string $description;

    public ?int $expirationDay;

    public ?Carbon $dateCreated;

    public ?string $customer;

    public ?string $paymentLink;

    public ?string $checkoutSession;

    public ?string $transactionReceiptUrl;

    public ?Chargeback $chargeback;

    public ?CreditCardTokenResponse $creditCard;

    public ?bool $deleted = null;

    /** @var Refund[]|null */
    public ?array $refunds = null;
}
