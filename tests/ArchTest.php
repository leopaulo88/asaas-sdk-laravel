<?php

// Architecture tests for Asaas SDK Laravel

arch('it will not use debugging functions')
    ->expect(['dd', 'dump', 'ray'])
    ->each->not->toBeUsed();

arch('entities should be classes')
    ->expect('Leopaulo88\Asaas\Entities')
    ->toBeClasses()
    ->ignoring('Leopaulo88\Asaas\Entities\Customer');

arch('resources should extend BaseResource')
    ->expect('Leopaulo88\Asaas\Resources')
    ->toExtend('Leopaulo88\Asaas\Resources\BaseResource')
    ->ignoring('Leopaulo88\Asaas\Resources\BaseResource');

arch('exceptions should extend AsaasException')
    ->expect('Leopaulo88\Asaas\Exceptions')
    ->toExtend('Leopaulo88\Asaas\Exceptions\AsaasException')
    ->ignoring('Leopaulo88\Asaas\Exceptions\AsaasException');

arch('contracts should be interfaces')
    ->expect('Leopaulo88\Asaas\Contracts')
    ->toBeInterfaces();

arch('concerns should be traits')
    ->expect('Leopaulo88\Asaas\Concerns')
    ->toBeTraits();

arch('facades should extend Laravel Facade')
    ->expect('Leopaulo88\Asaas\Facades')
    ->toExtend('Illuminate\Support\Facades\Facade');

arch('it should not use Laravel facades in entities')
    ->expect('Leopaulo88\Asaas\Entities')
    ->not->toUse([
        'Illuminate\Support\Facades\Log',
        'Illuminate\Support\Facades\Cache',
        'Illuminate\Support\Facades\DB'
    ]);

arch('it should not use Laravel helpers in entities')
    ->expect('Leopaulo88\Asaas\Entities')
    ->not->toUse(['collect', 'abort', 'redirect']);
