<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Leopaulo88\Asaas\Entities\List\ListResponse;
use Leopaulo88\Asaas\Entities\Transfer\TransferCreate;
use Leopaulo88\Asaas\Entities\Transfer\TransferResponse;
use Leopaulo88\Asaas\Resources\TransferResource;
use Leopaulo88\Asaas\Support\AsaasClient;

beforeEach(function () {
    $this->client = new AsaasClient('test_api_key', 'sandbox');
    $this->resource = new TransferResource($this->client);
});

it('can create transfer with array data', function () {
    $mockResponse = [
        'object' => 'transfer',
        'id' => 'tra_123456789',
        'value' => 250.00,
        'scheduleDate' => '2024-01-15',
        'type' => 'TED',
        'status' => 'PENDING',
        'dateCreated' => '2024-01-10 10:00:00',
        'effectiveDate' => '2024-01-15',
        'description' => 'Transferência teste',
        'operationType' => 'TED',
    ];

    Http::fake([
        '*' => Http::response($mockResponse, 200),
    ]);

    $transferData = [
        'value' => 250.00,
        'bankAccount' => [
            'bank' => [
                'code' => '033',
            ],
            'accountName' => 'João Silva',
            'ownerName' => 'João Silva',
            'cpfCnpj' => '12345678901',
            'agency' => '1234',
            'account' => '56789-0',
            'accountDigit' => '0',
        ],
        'scheduleDate' => '2024-01-15',
        'description' => 'Transferência teste',
    ];

    $result = $this->resource->create($transferData);

    expect($result)->toBeInstanceOf(TransferResponse::class)
        ->and($result->id)->toBe('tra_123456789')
        ->and($result->value)->toBe(250.00)
        ->and($result->type)->toBe('TED')
        ->and($result->status)->toBe('PENDING')
        ->and($result->scheduleDate)->toBeInstanceOf(Carbon::class)
        ->and($result->scheduleDate->format('Y-m-d'))->toBe('2024-01-15');
});

it('can create transfer with entity', function () {
    $mockResponse = [
        'object' => 'transfer',
        'id' => 'tra_987654321',
        'value' => 100.00,
        'scheduleDate' => '2024-01-20',
        'type' => 'PIX',
        'status' => 'PENDING',
        'dateCreated' => '2024-01-15 14:30:00',
        'operationType' => 'PIX',
    ];

    Http::fake([
        '*' => Http::response($mockResponse, 200),
    ]);

    $transferEntity = TransferCreate::make()
        ->value(100.00)
        ->pixAddressKey('11999999999')
        ->scheduleDate(Carbon::parse('2024-01-20'))
        ->description('Transferência PIX');

    $result = $this->resource->create($transferEntity);

    expect($result)->toBeInstanceOf(TransferResponse::class)
        ->and($result->id)->toBe('tra_987654321')
        ->and($result->value)->toBe(100.00)
        ->and($result->type)->toBe('PIX')
        ->and($result->status)->toBe('PENDING');
});

it('can list transfers without parameters', function () {
    $mockResponse = [
        'object' => 'list',
        'hasMore' => false,
        'totalCount' => 2,
        'limit' => 20,
        'offset' => 0,
        'data' => [
            [
                'object' => 'transfer',
                'id' => 'tra_123456789',
                'value' => 250.00,
                'scheduleDate' => '2024-01-15',
                'type' => 'TED',
                'status' => 'DONE',
                'dateCreated' => '2024-01-10 10:00:00',
            ],
            [
                'object' => 'transfer',
                'id' => 'tra_987654321',
                'value' => 100.00,
                'scheduleDate' => '2024-01-20',
                'type' => 'PIX',
                'status' => 'PENDING',
                'dateCreated' => '2024-01-15 14:30:00',
            ],
        ],
    ];

    Http::fake([
        '*' => Http::response($mockResponse, 200),
    ]);

    $result = $this->resource->list();

    expect($result)->toBeInstanceOf(ListResponse::class)
        ->and($result->totalCount)->toBe(2)
        ->and($result->hasMore)->toBeFalse()
        ->and($result->data)->toHaveCount(2)
        ->and($result->getData()[0])->toBeInstanceOf(TransferResponse::class)
        ->and($result->getData()[0]->id)->toBe('tra_123456789')
        ->and($result->getData()[0]->type)->toBe('TED')
        ->and($result->getData()[1]->id)->toBe('tra_987654321')
        ->and($result->getData()[1]->type)->toBe('PIX');
});

it('can list transfers with parameters', function () {
    $mockResponse = [
        'object' => 'list',
        'hasMore' => false,
        'totalCount' => 1,
        'limit' => 10,
        'offset' => 0,
        'data' => [
            [
                'object' => 'transfer',
                'id' => 'tra_123456789',
                'value' => 250.00,
                'scheduleDate' => '2024-01-15',
                'type' => 'TED',
                'status' => 'DONE',
                'dateCreated' => '2024-01-10 10:00:00',
                'operationType' => 'TED',
            ],
        ],
    ];

    Http::fake([
        '*' => Http::response($mockResponse, 200),
    ]);

    $params = [
        'dateCreated[ge]' => '2024-01-01',
        'dateCreated[le]' => '2024-01-31',
        'type' => 'TED',
        'limit' => 10,
        'offset' => 0,
    ];

    $result = $this->resource->list($params);

    expect($result)->toBeInstanceOf(ListResponse::class)
        ->and($result->totalCount)->toBe(1)
        ->and($result->getData())->toHaveCount(1)
        ->and($result->getData()[0]->type)->toBe('TED');
});

it('can find transfer by id', function () {
    $mockResponse = [
        'object' => 'transfer',
        'id' => 'tra_123456789',
        'value' => 250.00,
        'scheduleDate' => '2024-01-15',
        'type' => 'TED',
        'status' => 'DONE',
        'dateCreated' => '2024-01-10 10:00:00',
        'effectiveDate' => '2024-01-15',
        'description' => 'Transferência de teste',
        'operationType' => 'TED',
        'bankAccount' => [
            'bank' => [
                'code' => '033',
                'name' => 'Santander',
            ],
            'accountName' => 'João Silva',
            'ownerName' => 'João Silva',
            'cpfCnpj' => '12345678901',
            'agency' => '1234',
            'account' => '56789-0',
        ],
    ];

    Http::fake([
        '*' => Http::response($mockResponse, 200),
    ]);

    $result = $this->resource->find('tra_123456789');

    expect($result)->toBeInstanceOf(TransferResponse::class)
        ->and($result->id)->toBe('tra_123456789')
        ->and($result->value)->toBe(250.00)
        ->and($result->type)->toBe('TED')
        ->and($result->status)->toBe('DONE')
        ->and($result->description)->toBe('Transferência de teste')
        ->and($result->operationType)->toBe('TED');
});

it('can cancel transfer', function () {
    $mockResponse = [
        'object' => 'transfer',
        'id' => 'tra_123456789',
        'value' => 250.00,
        'scheduleDate' => '2024-01-15',
        'type' => 'TED',
        'status' => 'CANCELLED',
        'dateCreated' => '2024-01-10 10:00:00',
        'description' => 'Transferência cancelada',
        'operationType' => 'TED',
    ];

    Http::fake([
        '*' => Http::response($mockResponse, 200),
    ]);

    $result = $this->resource->cancel('tra_123456789');

    expect($result)->toBeInstanceOf(TransferResponse::class)
        ->and($result->id)->toBe('tra_123456789')
        ->and($result->status)->toBe('CANCELLED')
        ->and($result->value)->toBe(250.00);
});

it('handles different transfer types correctly', function () {
    // Test PIX transfer
    $pixResponse = [
        'object' => 'transfer',
        'id' => 'tra_pix_123',
        'value' => 150.00,
        'type' => 'PIX',
        'status' => 'DONE',
        'operationType' => 'PIX',
        'pixAddressKey' => '11999999999',
        'pixAddressKeyType' => 'PHONE',
    ];

    Http::fake([
        '*' => Http::response($pixResponse, 200),
    ]);

    $result = $this->resource->find('tra_pix_123');

    expect($result)->toBeInstanceOf(TransferResponse::class)
        ->and($result->type)->toBe('PIX')
        ->and($result->operationType)->toBe('PIX');
});

it('makes correct HTTP requests', function () {
    // Test create request
    Http::fake([
        '*/transfers' => Http::response([
            'object' => 'transfer',
            'id' => 'tra_create_123',
            'value' => 100.00,
            'type' => 'TED',
            'status' => 'PENDING',
        ], 200),
    ]);

    $result = $this->resource->create(['value' => 100.00]);
    expect($result)->toBeInstanceOf(TransferResponse::class);

    Http::assertSent(function ($request) {
        return $request->method() === 'POST' &&
               str_contains($request->url(), 'transfers') &&
               $request->data()['value'] === 100.00;
    });
});

it('makes correct HTTP requests for list', function () {
    // Test list request
    Http::fake([
        '*/transfers*' => Http::response([
            'object' => 'list',
            'data' => [],
            'hasMore' => false,
            'totalCount' => 0,
        ], 200),
    ]);

    $listResult = $this->resource->list(['type' => 'PIX']);
    expect($listResult)->toBeInstanceOf(ListResponse::class);

    Http::assertSent(function ($request) {
        return $request->method() === 'GET' &&
               str_contains($request->url(), 'transfers') &&
               str_contains($request->url(), 'type=PIX');
    });
});

it('makes correct HTTP requests for find', function () {
    // Test find request
    Http::fake([
        '*/transfers/tra_find_123' => Http::response([
            'object' => 'transfer',
            'id' => 'tra_find_123',
            'value' => 100.00,
            'type' => 'TED',
            'status' => 'PENDING',
        ], 200),
    ]);

    $findResult = $this->resource->find('tra_find_123');
    expect($findResult)->toBeInstanceOf(TransferResponse::class);

    Http::assertSent(function ($request) {
        return $request->method() === 'GET' &&
               str_contains($request->url(), 'transfers/tra_find_123');
    });
});

it('makes correct HTTP requests for cancel', function () {
    // Test cancel request
    Http::fake([
        '*/transfers/tra_cancel_456/cancel' => Http::response([
            'object' => 'transfer',
            'id' => 'tra_cancel_456',
            'value' => 100.00,
            'type' => 'TED',
            'status' => 'CANCELLED',
        ], 200),
    ]);

    $cancelResult = $this->resource->cancel('tra_cancel_456');
    expect($cancelResult)->toBeInstanceOf(TransferResponse::class);

    Http::assertSent(function ($request) {
        return $request->method() === 'DELETE' &&
               str_contains($request->url(), 'transfers/tra_cancel_456/cancel');
    });
});
