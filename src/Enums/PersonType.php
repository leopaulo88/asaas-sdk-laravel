<?php

namespace Leopaulo88\Asaas\Enums;

enum PersonType: string
{
    case INDIVIDUAL = 'cpf';
    case COMPANY = 'cnpj';

    public static function fromDocument(string $document): self
    {
        $cleanDocument = preg_replace('/\D/', '', $document);

        return strlen($cleanDocument) === 11
            ? self::INDIVIDUAL
            : self::COMPANY;
    }
}
