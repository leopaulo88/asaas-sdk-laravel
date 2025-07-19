<?php

namespace Leopaulo88\Asaas\Enums;

enum BillingType: string
{
    case UNDEFINED = 'UNDEFINED';
    case BOLETO = 'BOLETO';
    case CREDIT_CARD = 'CREDIT_CARD';
    case PIX = 'PIX';

    // only for response
    case DEBIT_CARD = 'DEBIT_CARD';
    case TRANSFER = 'TRANSFER';
    case DEPOSIT = 'DEPOSIT';
}
