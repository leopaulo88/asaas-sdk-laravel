<?php

namespace Leopaulo88\Asaas\Entities\Common;

use Leopaulo88\Asaas\Entities\BaseEntity;

class BankSlip extends BaseEntity
{
    public function __construct(
        public ?string $identificationField = null,
        public ?string $nossoNumero = null,
        public ?string $barCode = null,
        public ?string $bankSlipUrl = null,
        public ?int $daysAfterDueDateToRegistrationCancellation = null,
    ) {}
}
