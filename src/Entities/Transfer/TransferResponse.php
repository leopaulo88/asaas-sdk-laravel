<?php

namespace Leopaulo88\Asaas\Entities\Transfer;

use Carbon\Carbon;
use Leopaulo88\Asaas\Entities\BaseResponse;
use Leopaulo88\Asaas\Entities\Common\BankAccount;
use Leopaulo88\Asaas\Entities\Common\Discount;
use Leopaulo88\Asaas\Entities\Common\Fine;
use Leopaulo88\Asaas\Entities\Common\Interest;
use Leopaulo88\Asaas\Entities\Common\Split;
use Leopaulo88\Asaas\Entities\Common\TransferAccount;
use Leopaulo88\Asaas\Enums\BillingType;
use Leopaulo88\Asaas\Enums\SubscriptionCycle;
use Leopaulo88\Asaas\Enums\SubscriptionStatus;
use Leopaulo88\Asaas\Enums\TransferOperationType;
use Leopaulo88\Asaas\Enums\TransferStatus;
use Leopaulo88\Asaas\Enums\TransferType;

class TransferResponse extends BaseResponse
{
    public ?string $object;

    public ?string $id;

    public ?TransferType $type;

    public ?Carbon $dateCreated;

    public ?float $value;

    public ?float $netValue;

    public ?TransferStatus $status;

    public ?float $transferFee;

    public ?Carbon $effectiveDate;

    public ?Carbon $scheduleDate;

    public ?string $endToEndIdentifier;

    public ?bool $authorized;

    public ?string $failReason;

    public ?string $externalReference;

    public ?string $transactionReceiptUrl;

    public ?TransferOperationType $operationType;

    public ?string $description;

    public ?string $recurring;

    public ?BankAccount $bankAccount;

    public ?string $walletId;

    public ?TransferAccount $account;
}
