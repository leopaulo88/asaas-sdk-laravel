<?php

namespace Leopaulo88\Asaas\Facades;

use Illuminate\Support\Facades\Facade;

class Asaas extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'asaas';
    }
}
