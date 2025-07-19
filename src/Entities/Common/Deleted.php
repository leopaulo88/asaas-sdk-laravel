<?php

namespace Leopaulo88\Asaas\Entities\Common;

use Leopaulo88\Asaas\Entities\BaseEntity;
use Leopaulo88\Asaas\Enums\FineType;

class Deleted extends BaseEntity
{
    public function __construct(
        public ?bool $deleted = null,
        public ?string $id = null
    ){}
}