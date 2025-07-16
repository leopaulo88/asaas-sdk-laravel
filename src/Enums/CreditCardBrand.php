<?php

namespace Leopaulo88\Asaas\Enums;

enum CreditCardBrand: string
{
    case VISA = 'VISA';
    case MASTERCARD = 'MASTERCARD';
    case ELO = 'ELO';
    case DINERS = 'DINERS';
    case DISCOVER = 'DISCOVER';
    case AMEX = 'AMEX';
    case HIPERCARD = 'HIPERCARD';
    case CABAL = 'CABAL';
    case BANESCARD = 'BANESCARD';
    case CREDZ = 'CREDZ';
    case SOROCRED = 'SOROCRED';
    case CREDSYSTEM = 'CREDSYSTEM';
    case JCB = 'JCB';
    case UNKNOWN = 'UNKNOWN';

}