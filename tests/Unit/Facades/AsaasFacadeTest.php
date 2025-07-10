<?php

use Hubooai\Asaas\Asaas;
use Hubooai\Asaas\Endpoints\AccountEndpoint;
use Hubooai\Asaas\Facades\Asaas as AsaasFacade;

beforeEach(function () {
    $this->mockAsaas = Mockery::mock(Asaas::class);
    $this->mockAccountEndpoint = Mockery::mock(AccountEndpoint::class);
    $this->mockAsaas->shouldReceive('accounts')
        ->andReturn($this->mockAccountEndpoint);
    $this->app->instance(Asaas::class, $this->mockAsaas);
});

it('it can access accounts endpoint through facade', function () {
    $result = AsaasFacade::accounts();
    expect($result)->toBeInstanceOf(AccountEndpoint::class);
});

it('it resolves facade accessor correctly', function () {
    $accessor = AsaasFacade::getFacadeAccessor();
    expect($accessor)->toEqual(Asaas::class);
});
