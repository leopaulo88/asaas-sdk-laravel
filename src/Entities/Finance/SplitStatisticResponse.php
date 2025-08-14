<?php

namespace Leopaulo88\Asaas\Entities\Finance;

use Leopaulo88\Asaas\Entities\BaseResponse;

class SplitStatisticResponse extends BaseResponse
{
    public ?float $income = null;
    public ?float $outcome = null;
}
