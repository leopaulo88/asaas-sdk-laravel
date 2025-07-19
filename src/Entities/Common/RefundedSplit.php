<?php

namespace Leopaulo88\Asaas\Entities\Common;

use Leopaulo88\Asaas\Entities\BaseEntity;

class RefundedSplit extends BaseEntity
{
    public function __construct(
        public ?string $id = null,
        public ?float $value = null,
        public ?bool $done = null
    ) {}
}
