<?php

namespace Leopaulo88\Asaas\Entities\Common;

use Carbon\Carbon;
use Leopaulo88\Asaas\Entities\BaseEntity;

class Chargeback extends BaseEntity
{
    public function __construct(
        public ?string $id = null,
        public ?string $installment = null,
        public ?string $customerAccount = null,
        public ?string $payment = null,
        public ?string $status = null,
        public ?string $reason = null,
        public ?Carbon $disputeStartDate = null,
        public ?float $value = null,
        public ?Carbon $paymentDate = null,
        public ?ChargebackCreditCard $creditCard = null,
        public ?string $disputeStatus = null,
        public ?Carbon $deadlineToSendDisputeDocuments = null
    ) {}
}
