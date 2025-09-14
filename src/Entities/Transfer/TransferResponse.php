<?php

namespace Leopaulo88\Asaas\Entities\Transfer;

use Carbon\Carbon;
use Leopaulo88\Asaas\Entities\BaseResponse;
use Leopaulo88\Asaas\Entities\Common\BankAccount;
use Leopaulo88\Asaas\Entities\Common\TransferAccount;

class TransferResponse extends BaseResponse
{
    public ?string $object = null;

    public ?string $id = null;

    public ?string $type = null;

    public ?Carbon $dateCreated = null;

    public ?float $value = null;

    public ?float $netValue = null;

    public ?string $status = null;

    public ?float $transferFee = null;

    public ?Carbon $effectiveDate = null;

    public ?Carbon $scheduleDate = null;

    public ?string $endToEndIdentifier = null;

    public ?bool $authorized = null;

    public ?string $failReason = null;

    public ?string $externalReference = null;

    public ?string $transactionReceiptUrl = null;

    public ?string $operationType = null;

    public ?string $description = null;

    public ?string $recurring = null;

    public ?BankAccount $bankAccount = null;

    public ?string $walletId = null;

    public ?TransferAccount $account = null;
}
