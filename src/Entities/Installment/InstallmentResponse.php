<?php

namespace Leopaulo88\Asaas\Entities\Installment;

use Carbon\Carbon;
use Leopaulo88\Asaas\Entities\BaseResponse;
use Leopaulo88\Asaas\Entities\Common\Chargeback;
use Leopaulo88\Asaas\Entities\Common\Refund;
use Leopaulo88\Asaas\Entities\CreditCardToken\CreditCardTokenResponse;

class InstallmentResponse extends BaseResponse
{
    public ?string $object = null;

    public ?string $id = null;

    public ?float $value = null;

    public ?float $netValue = null;

    public ?float $paymentValue = null;

    public ?int $installmentCount = null;

    public ?string $billingType = null;

    public ?Carbon $paymentDate = null;

    public ?string $description = null;

    public ?int $expirationDay = null;

    public ?Carbon $dateCreated = null;

    public ?string $customer = null;

    public ?string $paymentLink = null;

    public ?string $checkoutSession = null;

    public ?string $transactionReceiptUrl = null;

    public ?Chargeback $chargeback = null;

    public ?CreditCardTokenResponse $creditCard = null;

    public ?bool $deleted = null;

    /** @var Refund[]|null */
    public ?array $refunds = null;
}
