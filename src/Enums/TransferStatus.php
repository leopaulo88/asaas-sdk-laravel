<?php

namespace Leopaulo88\Asaas\Enums;

enum TransferStatus: string
{
    case PENDING = 'PENDING';
    case BANK_PROCESSING = 'BANK_PROCESSING';
    case DONE = 'DONE';
    case CANCELLED = 'CANCELLED';
    case FAILED = 'FAILED';
}