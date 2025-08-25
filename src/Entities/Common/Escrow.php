<?php

namespace Leopaulo88\Asaas\Entities\Common;

use Carbon\Carbon;
use Leopaulo88\Asaas\Entities\BaseEntity;

class Escrow extends BaseEntity
{
    public function __construct(
        public ?string $id = null,
        public ?string $status = null,
        public ?Carbon $expirationDate = null,
        public ?Carbon $finishDate = null,
        public ?string $finishReason = null
    ) {}
}
