<?php

namespace Leopaulo88\Asaas\Entities\Pix;

use Leopaulo88\Asaas\Entities\BaseEntity;

class PixQrCode extends BaseEntity
{
    public ?string $encodedImage = null;

    public ?string $payload = null;
}
