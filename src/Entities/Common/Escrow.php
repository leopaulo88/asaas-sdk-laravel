<?php

namespace Leopaulo88\Asaas\Entities\Common;

use Carbon\Carbon;
use Leopaulo88\Asaas\Entities\BaseEntity;
use Leopaulo88\Asaas\Enums\EscrowFinishReason;
use Leopaulo88\Asaas\Enums\EscrowStatus;

class Escrow extends BaseEntity
{
    public function __construct(
        public ?string $id = null,
        public ?EscrowStatus $status = null,
        public ?Carbon $expirationDate = null,
        public ?Carbon $finishDate = null,
        public ?EscrowFinishReason $finishReason = null
    ) {}
}
