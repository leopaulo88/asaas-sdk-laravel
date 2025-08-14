<?php

use Leopaulo88\Asaas\Entities\Finance\SplitStatisticResponse;
use Leopaulo88\Asaas\Entities\Finance\StatisticResponse;
use Leopaulo88\Asaas\Resources\FinanceResource;
use PHPUnit\Framework\MockObject\MockObject;

describe('FinanceResource', function () {
    /** @var FinanceResource|MockObject $resource */
    beforeEach(function () {
        $this->resource = $this->getMockBuilder(FinanceResource::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['get'])
            ->getMock();
    });

    it('balance retorna BalanceResponse', function () {
        $mockResponse = ['balance' => 1234.56];
        $this->resource->expects($this->once())
            ->method('get')
            ->with('/finance/balance')
            ->willReturn($mockResponse);

        $result = $this->resource->balance();
        expect($result)->toBeInstanceOf(\Leopaulo88\Asaas\Entities\Finance\BalanceResponse::class);
        expect($result->balance)->toBe(1234.56);
    });

    it('statistics retorna StatisticResponse', function () {
        $mockResponse = ['quantity' => 10, 'value' => 1000.0, 'netValue' => 800.0];
        $this->resource->expects($this->once())
            ->method('get')
            ->with('/finance/payment/statistics', ['foo' => 'bar'])
            ->willReturn($mockResponse);

        $result = $this->resource->statistics(['foo' => 'bar']);
        expect($result)->toBeInstanceOf(StatisticResponse::class);
        expect($result->quantity)->toBe(10);
        expect($result->value)->toBe(1000.0);
        expect($result->netValue)->toBe(800.0);
    });

    it('splitStatistics retorna SplitStatisticResponse', function () {
        $mockResponse = ['income' => 500.0, 'outcome' => 400.0];
        $this->resource->expects($this->once())
            ->method('get')
            ->with('/finance/split/statistics')
            ->willReturn($mockResponse);

        $result = $this->resource->splitStatistics();
        expect($result)->toBeInstanceOf(SplitStatisticResponse::class);
        expect($result->income)->toBe(500.0);
        expect($result->outcome)->toBe(400.0);
    });
});
