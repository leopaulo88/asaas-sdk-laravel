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

class PaymentResponse extends BaseResponse
{
    public ?string $object = null;

    public ?string $id = null;

    public ?Carbon $dateCreated = null;

    public ?string $customer = null;

    public ?string $subscription = null;

    public ?string $installment = null;

    public ?string $checkoutSession = null;

    public ?string $paymentLink = null;

    public ?float $value = null;

    public ?float $netValue = null;

    public ?float $originalValue = null;

    public ?float $interestValue = null;

    public ?string $description = null;

    public ?string $billingType = null;

    public ?CreditCardTokenResponse $creditCard = null;

    public ?bool $canBePaidAfterDueDate = null;

    public ?string $pixTransaction = null;

    public ?string $pixQrCodeId = null;

    public ?string $status = null;

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
