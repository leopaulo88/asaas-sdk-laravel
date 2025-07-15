<?php

namespace Leopaulo88\AsaasSdkLaravel\Enums;

enum BrazilianState: string
{
    case ACRE = 'AC';
    case ALAGOAS = 'AL';
    case AMAPA = 'AP';
    case AMAZONAS = 'AM';
    case BAHIA = 'BA';
    case CEARA = 'CE';
    case DISTRITO_FEDERAL = 'DF';
    case ESPIRITO_SANTO = 'ES';
    case GOIAS = 'GO';
    case MARANHAO = 'MA';
    case MATO_GROSSO = 'MT';
    case MATO_GROSSO_DO_SUL = 'MS';
    case MINAS_GERAIS = 'MG';
    case PARA = 'PA';
    case PARAIBA = 'PB';
    case PARANA = 'PR';
    case PERNAMBUCO = 'PE';
    case PIAUI = 'PI';
    case RIO_DE_JANEIRO = 'RJ';
    case RIO_GRANDE_DO_NORTE = 'RN';
    case RIO_GRANDE_DO_SUL = 'RS';
    case RONDONIA = 'RO';
    case RORAIMA = 'RR';
    case SANTA_CATARINA = 'SC';
    case SAO_PAULO = 'SP';
    case SERGIPE = 'SE';
    case TOCANTINS = 'TO';

    public static function isValid(string $state): bool
    {
        return collect(self::cases())->contains('value', strtoupper($state));
    }
}
