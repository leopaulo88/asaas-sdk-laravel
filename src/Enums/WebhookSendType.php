<?php

namespace Leopaulo88\Asaas\Enums;

enum WebhookSendType: string
{
    case SEQUENTIALLY = 'SEQUENTIALLY';
    case NON_SEQUENTIALLY = 'NON_SEQUENTIALLY';
}
