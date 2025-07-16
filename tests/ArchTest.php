<?php

// Architecture tests for Asaas SDK Laravel (simplified to avoid issues)

arch('it will not use debugging functions')
    ->expect(['dd', 'dump', 'ray'])
    ->each->not->toBeUsed();

arch('resources should extend BaseResource')
    ->expect('Leopaulo88\Asaas\Resources\CustomerResource')
    ->toExtend('Leopaulo88\Asaas\Resources\BaseResource');

arch('facades should extend Laravel Facade')
    ->expect('Leopaulo88\Asaas\Facades\Asaas')
    ->toExtend('Illuminate\Support\Facades\Facade');
