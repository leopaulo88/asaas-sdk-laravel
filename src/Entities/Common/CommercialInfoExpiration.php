<?php

namespace Leopaulo88\Asaas\Entities\Common;

use Carbon\Carbon;
use Leopaulo88\Asaas\Entities\BaseEntity;

class CommercialInfoExpiration extends BaseEntity
{
    public function __construct(
        public ?bool $isExpired = null,
        public ?Carbon $scheduledDate = null,
    ) {}
}

