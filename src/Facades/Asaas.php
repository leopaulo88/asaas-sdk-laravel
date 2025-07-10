<?php

namespace Hubooai\Asaas\Facades;

use Hubooai\Asaas\Asaas as AsaasClient;
use Illuminate\Support\Facades\Facade;

class Asaas extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'asaas';
    }
}
