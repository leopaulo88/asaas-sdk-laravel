<?php

namespace Leopaulo88\Asaas\Enums;

enum ChargebackStatus: string
{
    case REQUESTED = 'REQUESTED';
    case IN_DISPUTE = 'IN_DISPUTE';
    case DISPUTE_LOST = 'DISPUTE_LOST';
    case REVERSED = 'REVERSED';
    case DONE = 'DONE';
}
