<?php

namespace Leopaulo88\Asaas\Enums;

enum SplitStatus: string
{
    case PENDING = 'PENDING';
    case AWAITING_CREDIT = 'AWAITING_CREDIT';
    case CANCELLED = 'CANCELLED';
    case DONE = 'DONE';
    case REFUNDED = 'REFUNDED';
    case BLOCKED_BY_VALUE_DIVERGENCE = 'BLOCKED_BY_VALUE_DIVERGENCE';
}
