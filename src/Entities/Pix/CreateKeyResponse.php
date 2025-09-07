<?php

namespace Leopaulo88\Asaas\Entities\Pix;

use Carbon\Carbon;
use Leopaulo88\Asaas\Entities\BaseResponse;

class CreateKeyResponse extends BaseResponse
{
    public ?string $id;

    public ?string $key;

    public ?string $type;

    public ?string $status;

    public ?Carbon $dateCreated;

    public ?bool $canBeDeleted;

    public ?string $cannotBeDeletedReason;

    public ?PixQrCode $qrCode;
}
