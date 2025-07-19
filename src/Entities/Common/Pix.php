<?php

namespace Leopaulo88\Asaas\Entities\Common;

use Carbon\Carbon;
use Leopaulo88\Asaas\Entities\BaseEntity;

class Pix extends BaseEntity
{

    public function __construct(
        public ?string $encodedImage = null,
        public ?string $payload = null,
        public ?Carbon $expirationDate = null,
    )
    {
    }
}