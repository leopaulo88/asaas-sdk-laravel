<?php

namespace Leopaulo88\Asaas\Entities\Payment;

use Carbon\Carbon;
use Leopaulo88\Asaas\Entities\BaseResponse;
use Leopaulo88\Asaas\Entities\Common\Chargeback;
use Leopaulo88\Asaas\Entities\Common\Discount;
use Leopaulo88\Asaas\Entities\Common\Escrow;
use Leopaulo88\Asaas\Entities\Common\Fine;
use Leopaulo88\Asaas\Entities\Common\Interest;
use Leopaulo88\Asaas\Entities\Common\Refund;
use Leopaulo88\Asaas\Entities\Common\Split;
use Leopaulo88\Asaas\Entities\CreditCardToken\CreditCardTokenResponse;
use Leopaulo88\Asaas\Enums\BillingType;
use Leopaulo88\Asaas\Enums\PaymentStatus;

class PaymentResponse extends BaseResponse
{
    public ?string $object;

    public ?string $id;

    public ?Carbon $dateCreated;

    public ?string $customer;

    public ?string $subscription;

    public ?string $installment;

    public ?string $checkoutSession;

    public ?string $paymentLink;

    public ?float $value;

    public ?float $netValue;

    public ?float $originalValue;

    public ?float $interestValue;

    public ?string $description;

    public ?BillingType $billingType;

    public ?CreditCardTokenResponse $creditCard;

    public ?bool $canBePaidAfterDueDate = null;

    public ?string $pixTransaction = null;

    public ?string $pixQrCodeId = null;

    public ?PaymentStatus $status = null;

    public ?Carbon $dueDate = null;

    public ?Carbon $originalDueDate = null;

    public ?Carbon $paymentDate = null;

    public ?Carbon $clientPaymentDate = null;

    public ?int $installmentNumber = null;

    public ?string $invoiceUrl = null;

    public ?string $invoiceNumber = null;

    public ?string $externalReference = null;

    public ?bool $deleted = null;

    public ?bool $anticipated = null;

    public ?bool $anticipable = null;

    public ?Carbon $creditDate = null;

    public ?Carbon $estimatedCreditDate = null;

    public ?string $transactionReceiptUrl = null;

    public ?string $nossoNumero = null;

    public ?string $bankSlipUrl = null;

    public ?Discount $discount = null;

    public ?Fine $fine = null;

    public ?Interest $interest = null;

    /** @var Split[]|null */
    public ?array $split = null;

    public ?bool $postalService = null;

    public ?int $daysAfterDueDateToRegistrationCancellation = null;

    public ?Chargeback $chargeback = null;

    public ?Escrow $escrow = null;

    /** @var Refund[]|null */
    public ?array $refunds = null;
}
