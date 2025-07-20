<?php

use Illuminate\Support\Facades\Http;
use Leopaulo88\Asaas\Entities\Common\Deleted;
use Leopaulo88\Asaas\Entities\Common\Split;
use Leopaulo88\Asaas\Entities\Installment\InstallmentResponse;
use Leopaulo88\Asaas\Entities\List\ListResponse;
use Leopaulo88\Asaas\Resources\InstallmentResource;
use Leopaulo88\Asaas\Support\AsaasClient;

beforeEach(function () {
    $this->client = new AsaasClient('test_api_key', 'sandbox');
    $this->resource = new InstallmentResource($this->client);
});

it('can create installment', function () {
    $mockResponse = [
        'object' => 'installment',
        'id' => 'ins_123456789',
        'customer' => 'cus_123456789',
        'value' => 150.00,
        'installmentCount' => 3,
        'billingType' => 'BOLETO',
        'dateCreated' => '2023-01-01 10:00:00',
    ];

    Http::fake([
        '*' => Http::response($mockResponse, 200),
    ]);

    $installmentData = [
        'customer' => 'cus_123456789',
        'value' => 150.00,
        'installmentCount' => 3,
        'dueDate' => '2023-02-01',
    ];

    $result = $this->resource->create($installmentData);

    expect($result)->toBeInstanceOf(InstallmentResponse::class)
        ->and($result->id)->toBe('ins_123456789')
        ->and($result->customer)->toBe('cus_123456789')
        ->and($result->value)->toBe(150.00)
        ->and($result->installmentCount)->toBe(3);
});

it('can find installment by id', function () {
    $mockResponse = [
        'object' => 'installment',
        'id' => 'ins_123456789',
        'customer' => 'cus_123456789',
        'value' => 150.00,
        'installmentCount' => 3,
        'billingType' => 'BOLETO',
    ];

    Http::fake([
        '*' => Http::response($mockResponse, 200),
    ]);

    $result = $this->resource->find('ins_123456789');

    expect($result)->toBeInstanceOf(InstallmentResponse::class)
        ->and($result->id)->toBe('ins_123456789')
        ->and($result->customer)->toBe('cus_123456789');
});

it('can list installments', function () {
    $mockResponse = [
        'object' => 'list',
        'hasMore' => false,
        'totalCount' => 2,
        'limit' => 20,
        'offset' => 0,
        'data' => [
            [
                'object' => 'installment',
                'id' => 'ins_123456789',
                'customer' => 'cus_123456789',
                'value' => 150.00,
                'billingType' => 'BOLETO',
            ],
            [
                'object' => 'installment',
                'id' => 'ins_987654321',
                'customer' => 'cus_987654321',
                'value' => 200.00,
                'billingType' => 'PIX',
            ],
        ],
    ];

    Http::fake([
        '*' => Http::response($mockResponse, 200),
    ]);

    $result = $this->resource->list();

    expect($result)->toBeInstanceOf(ListResponse::class)
        ->and($result->totalCount)->toBe(2)
        ->and($result->hasMore)->toBe(false)
        ->and($result->data)->toHaveCount(2);
});

it('can list installments with parameters', function () {
    $mockResponse = [
        'object' => 'list',
        'hasMore' => false,
        'totalCount' => 0,
        'limit' => 10,
        'offset' => 20,
        'data' => [],
    ];

    Http::fake([
        '*' => Http::response($mockResponse, 200),
    ]);

    $params = [
        'customer' => 'cus_123456789',
        'limit' => 10,
        'offset' => 20,
    ];

    $result = $this->resource->list($params);

    expect($result)->toBeInstanceOf(ListResponse::class);

    Http::assertSent(function ($request) {
        return str_contains($request->url(), 'customer=cus_123456789') &&
               str_contains($request->url(), 'limit=10') &&
               str_contains($request->url(), 'offset=20');
    });
});

it('can remove installment', function () {
    $mockResponse = [
        'deleted' => true,
        'id' => 'ins_123456789',
    ];

    Http::fake([
        '*' => Http::response($mockResponse, 200),
    ]);

    $result = $this->resource->remove('ins_123456789');

    expect($result)->toBeInstanceOf(Deleted::class)
        ->and($result->deleted)->toBe(true)
        ->and($result->id)->toBe('ins_123456789');
});

it('can list payments for installment', function () {
    $mockResponse = [
        'object' => 'list',
        'hasMore' => false,
        'totalCount' => 3,
        'data' => [
            [
                'object' => 'payment',
                'id' => 'pay_123456789',
                'installment' => 'ins_123456789',
                'value' => 50.00,
                'status' => 'RECEIVED',
            ],
            [
                'object' => 'payment',
                'id' => 'pay_987654321',
                'installment' => 'ins_123456789',
                'value' => 50.00,
                'status' => 'PENDING',
            ],
        ],
    ];

    Http::fake([
        '*' => Http::response($mockResponse, 200),
    ]);

    $result = $this->resource->listPayments('ins_123456789');

    expect($result)->toBeInstanceOf(ListResponse::class)
        ->and($result->totalCount)->toBe(3)
        ->and($result->data)->toHaveCount(2);
});

it('can list payments with status filter', function () {
    $mockResponse = [
        'object' => 'list',
        'hasMore' => false,
        'totalCount' => 0,
        'data' => [],
    ];

    Http::fake([
        '*' => Http::response($mockResponse, 200),
    ]);

    $params = ['status' => 'RECEIVED'];
    $result = $this->resource->listPayments('ins_123456789', $params);

    expect($result)->toBeInstanceOf(ListResponse::class);

    Http::assertSent(function ($request) {
        return str_contains($request->url(), 'status=RECEIVED');
    });
});

it('can refund installment', function () {
    $mockResponse = [
        'object' => 'installment',
        'id' => 'ins_123456789',
        'customer' => 'cus_123456789',
        'value' => 150.00,
        'refunds' => [
            [
                'status' => 'DONE',
                'value' => 150.00,
            ],
        ],
    ];

    Http::fake([
        '*' => Http::response($mockResponse, 200),
    ]);

    $result = $this->resource->refund('ins_123456789');

    expect($result)->toBeInstanceOf(InstallmentResponse::class)
        ->and($result->id)->toBe('ins_123456789')
        ->and($result->refunds)->toBeArray()
        ->and($result->refunds)->toHaveCount(1);
});

it('can update splits with array input', function () {
    $mockResponse = [
        'splits' => [
            [
                'walletId' => 'wallet_123',
                'fixedValue' => 25.00,
                'status' => 'PENDING',
            ],
            [
                'walletId' => 'wallet_456',
                'percentualValue' => 50.00,
                'status' => 'PENDING',
            ],
        ],
    ];

    Http::fake([
        '*' => Http::response($mockResponse, 200),
    ]);

    $splits = [
        [
            'walletId' => 'wallet_123',
            'fixedValue' => 25.00,
        ],
        [
            'walletId' => 'wallet_456',
            'percentualValue' => 50.00,
        ],
    ];

    $result = $this->resource->updateSplits('ins_123456789', $splits);

    expect($result)->toBeArray()
        ->and($result)->toHaveCount(2);

    foreach ($result as $split) {
        expect($split)->toBeInstanceOf(Split::class);
    }
});

it('can update splits with Split entity input', function () {
    $mockResponse = [
        'splits' => [
            [
                'walletId' => 'wallet_123',
                'fixedValue' => 30.00,
                'status' => 'PENDING',
            ],
        ],
    ];

    Http::fake([
        '*' => Http::response($mockResponse, 200),
    ]);

    $split = Split::make()
        ->walletId('wallet_123')
        ->fixedValue(30.00);

    $result = $this->resource->updateSplits('ins_123456789', [$split]);

    expect($result)->toBeArray()
        ->and($result)->toHaveCount(1)
        ->and($result[0])->toBeInstanceOf(Split::class)
        ->and($result[0]->walletId)->toBe('wallet_123')
        ->and($result[0]->fixedValue)->toBe(30.00);
});

it('can handle empty splits response', function () {
    $mockResponse = [
        'splits' => [],
    ];

    Http::fake([
        '*' => Http::response($mockResponse, 200),
    ]);

    $result = $this->resource->updateSplits('ins_123456789', []);

    expect($result)->toBeArray()
        ->and($result)->toHaveCount(0);
});

it('handles create validation errors', function () {
    Http::fake([
        '*' => Http::response([
            'errors' => [
                ['code' => 'invalid_customer', 'description' => 'Customer is required'],
            ],
        ], 400),
    ]);

    expect(fn () => $this->resource->create([]))
        ->toThrow(\Exception::class);
});

it('handles find not found error', function () {
    Http::fake([
        '*' => Http::response([
            'errors' => [
                ['code' => 'not_found', 'description' => 'Installment not found'],
            ],
        ], 404),
    ]);

    expect(fn () => $this->resource->find('ins_invalid'))
        ->toThrow(\Exception::class);
});

it('handles refund errors', function () {
    Http::fake([
        '*' => Http::response([
            'errors' => [
                ['code' => 'invalid_refund', 'description' => 'Cannot refund this installment'],
            ],
        ], 400),
    ]);

    expect(fn () => $this->resource->refund('ins_123456789'))
        ->toThrow(\Exception::class);
});

it('handles updateSplits errors', function () {
    Http::fake([
        '*' => Http::response([
            'errors' => [
                ['code' => 'invalid_splits', 'description' => 'Invalid splits configuration'],
            ],
        ], 400),
    ]);

    $splits = [
        ['walletId' => 'invalid_wallet', 'fixedValue' => -10.00],
    ];

    expect(fn () => $this->resource->updateSplits('ins_123456789', $splits))
        ->toThrow(\Exception::class);
});

it('handles network errors', function () {
    Http::fake([
        '*' => function () {
            throw new \Exception('Network error');
        },
    ]);

    expect(fn () => $this->resource->find('ins_123'))
        ->toThrow(\Exception::class, 'Network error');
});
