<?php

namespace Leopaulo88\Asaas\Enums;

enum MyAccountStatus: string
{
    case APPROVED = 'APPROVED';
    case PENDING = 'PENDING';
    case REJECTED = 'REJECTED';
    case AWAITING_APPROVAL = 'AWAITING_APPROVAL';
}
