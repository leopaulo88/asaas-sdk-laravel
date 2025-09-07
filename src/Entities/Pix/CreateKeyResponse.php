<?php

namespace Leopaulo88\Asaas\Entities\Pix;

use Carbon\Carbon;
use Leopaulo88\Asaas\Entities\BaseResponse;

class CreateKeyResponse extends BaseResponse
{
    public ?string $id = null;

    public ?string $key = null;

    public ?string $type = null;

    public ?string $status = null;

    public ?Carbon $dateCreated = null;

    public ?bool $canBeDeleted = null;

    public ?string $cannotBeDeletedReason = null;

    public ?PixQrCode $qrCode = null;
}
