<?php

namespace Leopaulo88\Asaas\Enums;

enum PersonType: string
{
    case FISICA = 'FISICA';
    case JURIDICA = 'JURIDICA';

    public static function fromDocument(string $document): self
    {
        $cleanDocument = preg_replace('/\D/', '', $document);

        return strlen($cleanDocument) === 11
            ? self::FISICA
            : self::JURIDICA;
    }
}
