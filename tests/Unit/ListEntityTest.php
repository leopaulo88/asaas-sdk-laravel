<?php

use Leopaulo88\Asaas\Entities\Responses\ListResponse;
use Leopaulo88\Asaas\Entities\Responses\CustomerResponse;

describe('List Entity', function () {

    describe('basic properties', function () {

        it('can create list response from api data', function () {
            $apiData = [
                'object' => 'list',
                'hasMore' => true,
                'totalCount' => 100,
                'limit' => 10,
                'offset' => 20,
                'data' => []
            ];

            $response = ListResponse::fromArray($apiData);

            expect($response)->toBeInstanceOf(ListResponse::class)
                ->and($response->hasMore())->toBe(true)
                ->and($response->getTotalCount())->toBe(100)
                ->and($response->getLimit())->toBe(10)
                ->and($response->getOffset())->toBe(20);
        });

        it('handles empty data correctly', function () {
            $apiData = [
                'object' => 'list',
                'hasMore' => false,
                'totalCount' => 0,
                'limit' => 10,
                'offset' => 0,
                'data' => []
            ];

            $response = ListResponse::fromArray($apiData);

            expect($response->isEmpty())->toBe(true)
                ->and($response->count())->toBe(0);
        });

    });

    describe('pagination methods', function () {

        it('calculates pagination correctly', function () {
            $apiData = [
                'object' => 'list',
                'hasMore' => true,
                'totalCount' => 100,
                'limit' => 10,
                'offset' => 20,
                'data' => [],
            ];

            $response = ListResponse::fromArray($apiData);

            expect($response->isFirstPage())->toBe(false)
                ->and($response->isLastPage())->toBe(false)
                ->and($response->getCurrentPage())->toBe(3)
                ->and($response->getTotalPages())->toBe(10);
        });

        it('handles first page pagination', function () {
            $apiData = [
                'object' => 'list',
                'hasMore' => true,
                'totalCount' => 50,
                'limit' => 10,
                'offset' => 0,
                'data' => [],
            ];

            $response = ListResponse::fromArray($apiData);

            expect($response->isFirstPage())->toBe(true)
                ->and($response->isLastPage())->toBe(false)
                ->and($response->getCurrentPage())->toBe(1);
        });

        it('handles last page pagination', function () {
            $apiData = [
                'object' => 'list',
                'hasMore' => false,
                'totalCount' => 50,
                'limit' => 10,
                'offset' => 40,
                'data' => [],
            ];

            $response = ListResponse::fromArray($apiData);

            expect($response->isFirstPage())->toBe(false)
                ->and($response->isLastPage())->toBe(true)
                ->and($response->getCurrentPage())->toBe(5);
        });

    });

});
