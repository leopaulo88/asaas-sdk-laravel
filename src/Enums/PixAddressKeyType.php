<?php

namespace Leopaulo88\Asaas\Enums;

enum PixAddressKeyType: string
{
    case CPF = 'CPF';
    case CNPJ = 'CNPJ';
    case EMAIL = 'EMAIL';
    case PHONE = 'PHONE';
    case EVP = 'EVP';
}
