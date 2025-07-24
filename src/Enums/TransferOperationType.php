<?php

namespace Leopaulo88\Asaas\Enums;

enum TransferOperationType: string
{
    case PIX = 'PIX';
    case TED = 'TED';
    case INTERNAL = 'INTERNAL';
}
