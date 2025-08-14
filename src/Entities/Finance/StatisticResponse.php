<?php

namespace Leopaulo88\Asaas\Entities\Finance;

use Leopaulo88\Asaas\Entities\BaseResponse;

class StatisticResponse extends BaseResponse
{
    public ?int $quantity = null;

    public ?float $value = null;

    public ?float $netValue = null;
}
