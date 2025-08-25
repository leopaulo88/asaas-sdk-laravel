<?php

namespace Leopaulo88\Asaas\Entities\Transfer;

use Carbon\Carbon;
use Leopaulo88\Asaas\Entities\BaseResponse;
use Leopaulo88\Asaas\Entities\Common\BankAccount;
use Leopaulo88\Asaas\Entities\Common\TransferAccount;

class TransferResponse extends BaseResponse
{
    public ?string $object;

    public ?string $id;

    public ?string $type;

    public ?Carbon $dateCreated;

    public ?float $value;

    public ?float $netValue;

    public ?string $status;

    public ?float $transferFee;

    public ?Carbon $effectiveDate;

    public ?Carbon $scheduleDate;

    public ?string $endToEndIdentifier;

    public ?bool $authorized;

    public ?string $failReason;

    public ?string $externalReference;

    public ?string $transactionReceiptUrl;

    public ?string $operationType;

    public ?string $description;

    public ?string $recurring;

    public ?BankAccount $bankAccount;

    public ?string $walletId;

    public ?TransferAccount $account;
}
