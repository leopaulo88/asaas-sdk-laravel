<?php

use Illuminate\Support\Facades\Http;
use Leopaulo88\Asaas\Entities\Common\Deleted;
use Leopaulo88\Asaas\Entities\List\ListResponse;
use Leopaulo88\Asaas\Entities\Payment\BillingInfoResponse;
use Leopaulo88\Asaas\Entities\Payment\PaymentCreate;
use Leopaulo88\Asaas\Entities\Payment\PaymentCreditCard;
use Leopaulo88\Asaas\Entities\Payment\PaymentResponse;
use Leopaulo88\Asaas\Entities\Payment\PaymentUpdate;
use Leopaulo88\Asaas\Resources\PaymentResource;
use Leopaulo88\Asaas\Support\AsaasClient;

beforeEach(function () {
    $this->client = new AsaasClient('test_api_key', 'sandbox');
    $this->paymentResource = new PaymentResource($this->client);
});

describe('PaymentResource', function () {

    describe('list method', function () {

        it('should list payments with correct endpoint', function () {
            Http::fake([
                'https://sandbox.asaas.com/api/v3/payments' => Http::response([
                    'object' => 'list',
                    'hasMore' => false,
                    'totalCount' => 0,
                    'limit' => 20,
                    'offset' => 0,
                    'data' => [],
                ]),
            ]);

            $result = $this->paymentResource->list();

            expect($result)->toBeInstanceOf(ListResponse::class);

            Http::assertSent(function ($request) {
                return $request->url() === 'https://sandbox.asaas.com/api/v3/payments'
                    && $request->method() === 'GET';
            });
        });

        it('should pass query parameters correctly', function () {
            Http::fake([
                'https://sandbox.asaas.com/api/v3/payments*' => Http::response([
                    'object' => 'list',
                    'hasMore' => false,
                    'totalCount' => 2,
                    'limit' => 10,
                    'offset' => 0,
                    'data' => [],
                ]),
            ]);

            $params = [
                'customer' => 'cus_123',
                'status' => 'PENDING',
                'limit' => 10,
                'offset' => 0,
            ];

            $result = $this->paymentResource->list($params);

            expect($result)->toBeInstanceOf(ListResponse::class);

            Http::assertSent(function ($request) use ($params) {
                foreach ($params as $key => $value) {
                    if (! str_contains($request->url(), "{$key}={$value}")) {
                        return false;
                    }
                }

                return true;
            });
        });

        it('should return payments list with data', function () {
            Http::fake([
                'https://sandbox.asaas.com/api/v3/payments' => Http::response([
                    'object' => 'list',
                    'hasMore' => true,
                    'totalCount' => 50,
                    'limit' => 10,
                    'offset' => 0,
                    'data' => [
                        [
                            'object' => 'payment',
                            'id' => 'pay_123',
                            'customer' => 'cus_123',
                            'status' => 'PENDING',
                            'value' => 100.50,
                            'description' => 'Pagamento teste',
                            'billingType' => 'BOLETO',
                            'dueDate' => '2025-02-15',
                        ],
                        [
                            'object' => 'payment',
                            'id' => 'pay_456',
                            'customer' => 'cus_456',
                            'status' => 'RECEIVED',
                            'value' => 250.00,
                            'description' => 'Pagamento teste 2',
                            'billingType' => 'PIX',
                            'dueDate' => '2025-02-20',
                        ],
                    ],
                ]),
            ]);

            $result = $this->paymentResource->list();

            expect($result)->toBeInstanceOf(ListResponse::class)
                ->and($result->hasMore())->toBe(true)
                ->and($result->getTotalCount())->toBe(50)
                ->and($result->count())->toBe(2);

            $payments = $result->getData();
            expect($payments)->toHaveCount(2)
                ->and($payments[0])->toBeInstanceOf(PaymentResponse::class)
                ->and($payments[0]->id)->toBe('pay_123')
                ->and($payments[1])->toBeInstanceOf(PaymentResponse::class)
                ->and($payments[1]->id)->toBe('pay_456');
        });

    });

    describe('create method', function () {

        it('should create payment with array data', function () {
            $paymentData = [
                'customer' => 'cus_123',
                'billingType' => 'BOLETO',
                'value' => 150.00,
                'dueDate' => '2025-02-15',
                'description' => 'Pagamento de teste',
            ];

            Http::fake([
                'https://sandbox.asaas.com/api/v3/payments' => Http::response([
                    'object' => 'payment',
                    'id' => 'pay_new_123',
                    'customer' => 'cus_123',
                    'status' => 'PENDING',
                    'value' => 150.00,
                    'description' => 'Pagamento de teste',
                    'billingType' => 'BOLETO',
                    'dueDate' => '2025-02-15',
                    'dateCreated' => '2025-01-15T10:30:00.000Z',
                ]),
            ]);

            $result = $this->paymentResource->create($paymentData);

            expect($result)->toBeInstanceOf(PaymentResponse::class)
                ->and($result->id)->toBe('pay_new_123')
                ->and($result->value)->toBe(150.00)
                ->and($result->description)->toBe('Pagamento de teste');
        });

        it('should create payment with PaymentCreate entity', function () {
            $paymentCreate = new PaymentCreate;
            $paymentCreate->customer = 'cus_456';
            $paymentCreate->value = 200.00;

            Http::fake([
                'https://sandbox.asaas.com/api/v3/payments' => Http::response([
                    'object' => 'payment',
                    'id' => 'pay_entity_456',
                    'customer' => 'cus_456',
                    'status' => 'PENDING',
                    'value' => 200.00,
                    'billingType' => 'BOLETO',
                    'dateCreated' => '2025-01-15T10:30:00.000Z',
                ]),
            ]);

            $result = $this->paymentResource->create($paymentCreate);

            expect($result)->toBeInstanceOf(PaymentResponse::class)
                ->and($result->id)->toBe('pay_entity_456')
                ->and($result->value)->toBe(200.00);
        });

        it('should send correct request data', function () {
            Http::fake([
                'https://sandbox.asaas.com/api/v3/payments' => Http::response([
                    'object' => 'payment',
                    'id' => 'pay_test',
                    'status' => 'PENDING',
                ]),
            ]);

            $paymentData = [
                'customer' => 'cus_test',
                'billingType' => 'PIX',
                'value' => 99.99,
                'description' => 'Test payment',
            ];

            $this->paymentResource->create($paymentData);

            Http::assertSent(function ($request) use ($paymentData) {
                $body = json_decode($request->body(), true);

                return $request->method() === 'POST'
                    && $request->url() === 'https://sandbox.asaas.com/api/v3/payments'
                    && $body['customer'] === $paymentData['customer']
                    && $body['billingType'] === $paymentData['billingType']
                    && $body['value'] === $paymentData['value'];
            });
        });

    });

    describe('find method', function () {

        it('should find payment by id', function () {
            $paymentId = 'pay_123';

            Http::fake([
                "https://sandbox.asaas.com/api/v3/payments/{$paymentId}" => Http::response([
                    'object' => 'payment',
                    'id' => $paymentId,
                    'customer' => 'cus_123',
                    'status' => 'RECEIVED',
                    'value' => 300.00,
                    'description' => 'Pagamento encontrado',
                    'billingType' => 'CREDIT_CARD',
                    'dateCreated' => '2025-01-15T10:30:00.000Z',
                ]),
            ]);

            $result = $this->paymentResource->find($paymentId);

            expect($result)->toBeInstanceOf(PaymentResponse::class)
                ->and($result->id)->toBe($paymentId)
                ->and($result->value)->toBe(300.00)
                ->and($result->description)->toBe('Pagamento encontrado');
        });

        it('should call correct endpoint', function () {
            Http::fake([
                'https://sandbox.asaas.com/api/v3/payments/pay_test' => Http::response([
                    'object' => 'payment',
                    'id' => 'pay_test',
                    'status' => 'PENDING',
                ]),
            ]);

            $paymentId = 'pay_test';
            $result = $this->paymentResource->find($paymentId);

            expect($result)->toBeInstanceOf(PaymentResponse::class);

            Http::assertSent(function ($request) use ($paymentId) {
                return $request->method() === 'GET'
                    && $request->url() === "https://sandbox.asaas.com/api/v3/payments/{$paymentId}";
            });
        });

    });

    describe('update method', function () {

        it('should update payment with array data', function () {
            $paymentId = 'pay_123';
            $updateData = [
                'description' => 'Pagamento atualizado',
                'value' => 175.50,
            ];

            Http::fake([
                "https://sandbox.asaas.com/api/v3/payments/{$paymentId}" => Http::response([
                    'object' => 'payment',
                    'id' => $paymentId,
                    'customer' => 'cus_123',
                    'status' => 'PENDING',
                    'value' => 175.50,
                    'description' => 'Pagamento atualizado',
                    'billingType' => 'BOLETO',
                    'dateCreated' => '2025-01-15T10:30:00.000Z',
                ]),
            ]);

            $result = $this->paymentResource->update($paymentId, $updateData);

            expect($result)->toBeInstanceOf(PaymentResponse::class)
                ->and($result->description)->toBe('Pagamento atualizado')
                ->and($result->value)->toBe(175.50);
        });

        it('should update payment with PaymentUpdate entity', function () {
            $paymentId = 'pay_456';
            $paymentUpdate = new PaymentUpdate;
            $paymentUpdate->description = 'Updated via entity';

            Http::fake([
                "https://sandbox.asaas.com/api/v3/payments/{$paymentId}" => Http::response([
                    'object' => 'payment',
                    'id' => $paymentId,
                    'description' => 'Updated via entity',
                    'status' => 'PENDING',
                ]),
            ]);

            $result = $this->paymentResource->update($paymentId, $paymentUpdate);

            expect($result)->toBeInstanceOf(PaymentResponse::class)
                ->and($result->description)->toBe('Updated via entity');
        });

    });

    describe('delete method', function () {

        it('should delete payment and return Deleted object', function () {
            $paymentId = 'pay_123';

            Http::fake([
                "https://sandbox.asaas.com/api/v3/payments/{$paymentId}" => Http::response([
                    'id' => $paymentId,
                    'deleted' => true,
                ]),
            ]);

            $result = $this->paymentResource->remove($paymentId);

            expect($result)->toBeInstanceOf(Deleted::class)
                ->and($result->id)->toBe($paymentId)
                ->and($result->deleted)->toBe(true);
        });

        it('should call DELETE method', function () {
            Http::fake([
                'https://sandbox.asaas.com/api/v3/payments/pay_test' => Http::response([
                    'id' => 'pay_test',
                    'deleted' => true,
                ]),
            ]);

            $paymentId = 'pay_test';
            $result = $this->paymentResource->remove($paymentId);

            expect($result)->toBeInstanceOf(Deleted::class);

            Http::assertSent(function ($request) use ($paymentId) {
                return $request->method() === 'DELETE'
                    && $request->url() === "https://sandbox.asaas.com/api/v3/payments/{$paymentId}";
            });
        });

    });

    describe('restore method', function () {

        it('should restore deleted payment', function () {
            $paymentId = 'pay_123';

            Http::fake([
                "https://sandbox.asaas.com/api/v3/payments/{$paymentId}/restore" => Http::response([
                    'object' => 'payment',
                    'id' => $paymentId,
                    'customer' => 'cus_123',
                    'status' => 'PENDING',
                    'value' => 100.00,
                    'description' => 'Pagamento restaurado',
                    'deleted' => false,
                ]),
            ]);

            $result = $this->paymentResource->restore($paymentId);

            expect($result)->toBeInstanceOf(PaymentResponse::class)
                ->and($result->id)->toBe($paymentId)
                ->and($result->deleted)->toBe(false);
        });

    });

    describe('captureAuthorizedPayment method', function () {

        it('should capture authorized payment', function () {
            $paymentId = 'pay_auth_123';

            Http::fake([
                "https://sandbox.asaas.com/api/v3/payments/{$paymentId}/captureAuthorizedPayment" => Http::response([
                    'object' => 'payment',
                    'id' => $paymentId,
                    'status' => 'RECEIVED',
                    'value' => 250.00,
                    'description' => 'Pagamento capturado',
                ]),
            ]);

            $result = $this->paymentResource->captureAuthorizedPayment($paymentId);

            expect($result)->toBeInstanceOf(PaymentResponse::class)
                ->and($result->id)->toBe($paymentId)
                ->and($result->status->value)->toBe('RECEIVED');

            Http::assertSent(function ($request) use ($paymentId) {
                return $request->method() === 'POST'
                    && $request->url() === "https://sandbox.asaas.com/api/v3/payments/{$paymentId}/captureAuthorizedPayment";
            });
        });

    });

    describe('payWithCreditCard method', function () {

        it('should pay with credit card using array data', function () {
            $paymentId = 'pay_cc_123';
            $creditCardData = [
                'holderName' => 'João Silva',
                'number' => '4111111111111111',
                'expiryMonth' => '12',
                'expiryYear' => '2028',
                'ccv' => '123',
            ];

            Http::fake([
                "https://sandbox.asaas.com/api/v3/payments/{$paymentId}/payWithCreditCard" => Http::response([
                    'object' => 'payment',
                    'id' => $paymentId,
                    'status' => 'RECEIVED',
                    'value' => 350.00,
                    'billingType' => 'CREDIT_CARD',
                ]),
            ]);

            $result = $this->paymentResource->payWithCreditCard($paymentId, $creditCardData);

            expect($result)->toBeInstanceOf(PaymentResponse::class)
                ->and($result->id)->toBe($paymentId)
                ->and($result->status->value)->toBe('RECEIVED');
        });

        it('should pay with credit card using PaymentCreditCard entity', function () {
            $paymentId = 'pay_cc_456';
            $creditCard = new PaymentCreditCard;

            Http::fake([
                "https://sandbox.asaas.com/api/v3/payments/{$paymentId}/payWithCreditCard" => Http::response([
                    'object' => 'payment',
                    'id' => $paymentId,
                    'status' => 'RECEIVED',
                    'billingType' => 'CREDIT_CARD',
                ]),
            ]);

            $result = $this->paymentResource->payWithCreditCard($paymentId, $creditCard);

            expect($result)->toBeInstanceOf(PaymentResponse::class)
                ->and($result->id)->toBe($paymentId);
        });

    });

    describe('billingInfo method', function () {

        it('should retrieve billing information', function () {
            $paymentId = 'pay_billing_123';

            Http::fake([
                "https://sandbox.asaas.com/api/v3/payments/{$paymentId}/billingInfo" => Http::response([
                    'email' => 'cliente@teste.com',
                    'phone' => '11999999999',
                    'name' => 'João Silva',
                    'cpfCnpj' => '12345678901',
                    'postalCode' => '12345678',
                    'address' => 'Rua Teste, 123',
                ]),
            ]);

            $result = $this->paymentResource->billingInfo($paymentId);

            expect($result)->toBeInstanceOf(BillingInfoResponse::class);

            Http::assertSent(function ($request) use ($paymentId) {
                return $request->method() === 'GET'
                    && $request->url() === "https://sandbox.asaas.com/api/v3/payments/{$paymentId}/billingInfo";
            });
        });

    });

});
