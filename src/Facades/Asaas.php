<?php

namespace Hubooai\Asaas\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Hubooai\Asaas\Asaas
 */
class Asaas extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Hubooai\Asaas\Asaas::class;
    }
}
