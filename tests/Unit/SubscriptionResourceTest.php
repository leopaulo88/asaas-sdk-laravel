<?php

use Illuminate\Support\Facades\Http;
use Leopaulo88\Asaas\Entities\Common\Deleted;
use Leopaulo88\Asaas\Entities\List\ListResponse;
use Leopaulo88\Asaas\Entities\Subscription\SubscriptionCreate;
use Leopaulo88\Asaas\Entities\Subscription\SubscriptionResponse;
use Leopaulo88\Asaas\Entities\Subscription\SubscriptionUpdate;
use Leopaulo88\Asaas\Entities\Subscription\SubscriptionUpdateCreditCard;
use Leopaulo88\Asaas\Resources\SubscriptionResource;
use Leopaulo88\Asaas\Support\AsaasClient;

beforeEach(function () {
    $this->client = new AsaasClient('test_api_key', 'sandbox');
    $this->subscriptionResource = new SubscriptionResource($this->client);
});

describe('SubscriptionResource', function () {

    describe('create method', function () {

        it('should create subscription with array data', function () {
            $subscriptionData = [
                'customer' => 'cus_123',
                'billingType' => 'BOLETO',
                'value' => 99.90,
                'nextDueDate' => '2025-02-15',
                'cycle' => 'MONTHLY',
                'description' => 'Assinatura mensal de teste',
            ];

            Http::fake([
                'https://sandbox.asaas.com/api/v3/subscriptions' => Http::response([
                    'object' => 'subscription',
                    'id' => 'sub_123',
                    'customer' => 'cus_123',
                    'status' => 'ACTIVE',
                    'value' => 99.90,
                    'nextDueDate' => '2025-02-15',
                    'cycle' => 'MONTHLY',
                    'billingType' => 'BOLETO',
                    'description' => 'Assinatura mensal de teste',
                    'dateCreated' => '2025-01-15 10:30:00',
                ]),
            ]);

            $result = $this->subscriptionResource->create($subscriptionData);

            expect($result)->toBeInstanceOf(SubscriptionResponse::class)
                ->and($result->id)->toBe('sub_123')
                ->and($result->value)->toBe(99.90)
                ->and($result->description)->toBe('Assinatura mensal de teste');
        });

        it('should create subscription with SubscriptionCreate entity', function () {
            $subscriptionCreate = new SubscriptionCreate;
            $subscriptionCreate->customer = 'cus_456';
            $subscriptionCreate->value = 149.90;

            Http::fake([
                'https://sandbox.asaas.com/api/v3/subscriptions' => Http::response([
                    'object' => 'subscription',
                    'id' => 'sub_456',
                    'customer' => 'cus_456',
                    'status' => 'ACTIVE',
                    'value' => 149.90,
                    'billingType' => 'CREDIT_CARD',
                    'dateCreated' => '2025-01-15 10:30:00',
                ]),
            ]);

            $result = $this->subscriptionResource->create($subscriptionCreate);

            expect($result)->toBeInstanceOf(SubscriptionResponse::class)
                ->and($result->id)->toBe('sub_456')
                ->and($result->value)->toBe(149.90);
        });

        it('should send correct request data', function () {
            Http::fake([
                'https://sandbox.asaas.com/api/v3/subscriptions' => Http::response([
                    'object' => 'subscription',
                    'id' => 'sub_test',
                    'status' => 'ACTIVE',
                ]),
            ]);

            $subscriptionData = [
                'customer' => 'cus_test',
                'billingType' => 'PIX',
                'value' => 79.99,
                'cycle' => 'WEEKLY',
            ];

            $this->subscriptionResource->create($subscriptionData);

            Http::assertSent(function ($request) use ($subscriptionData) {
                $body = json_decode($request->body(), true);

                return $request->method() === 'POST'
                    && $request->url() === 'https://sandbox.asaas.com/api/v3/subscriptions'
                    && $body['customer'] === $subscriptionData['customer']
                    && $body['billingType'] === $subscriptionData['billingType']
                    && $body['value'] === $subscriptionData['value'];
            });
        });

    });

    describe('list method', function () {

        it('should list subscriptions with correct endpoint', function () {
            Http::fake([
                'https://sandbox.asaas.com/api/v3/subscriptions' => Http::response([
                    'object' => 'list',
                    'hasMore' => false,
                    'totalCount' => 0,
                    'limit' => 20,
                    'offset' => 0,
                    'data' => [],
                ]),
            ]);

            $result = $this->subscriptionResource->list();

            expect($result)->toBeInstanceOf(ListResponse::class);

            Http::assertSent(function ($request) {
                return $request->url() === 'https://sandbox.asaas.com/api/v3/subscriptions'
                    && $request->method() === 'GET';
            });
        });

        it('should pass query parameters correctly', function () {
            Http::fake([
                'https://sandbox.asaas.com/api/v3/subscriptions*' => Http::response([
                    'object' => 'list',
                    'hasMore' => false,
                    'totalCount' => 3,
                    'limit' => 10,
                    'offset' => 0,
                    'data' => [],
                ]),
            ]);

            $params = [
                'customer' => 'cus_123',
                'status' => 'ACTIVE',
                'billingType' => 'CREDIT_CARD',
                'limit' => 10,
                'offset' => 0,
            ];

            $result = $this->subscriptionResource->list($params);

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

        it('should return subscriptions list with data', function () {
            Http::fake([
                'https://sandbox.asaas.com/api/v3/subscriptions' => Http::response([
                    'object' => 'list',
                    'hasMore' => true,
                    'totalCount' => 25,
                    'limit' => 10,
                    'offset' => 0,
                    'data' => [
                        [
                            'object' => 'subscription',
                            'id' => 'sub_123',
                            'customer' => 'cus_123',
                            'status' => 'ACTIVE',
                            'value' => 99.90,
                            'cycle' => 'MONTHLY',
                            'billingType' => 'BOLETO',
                            'nextDueDate' => '2025-02-15',
                        ],
                        [
                            'object' => 'subscription',
                            'id' => 'sub_456',
                            'customer' => 'cus_456',
                            'status' => 'INACTIVE',
                            'value' => 149.90,
                            'cycle' => 'QUARTERLY',
                            'billingType' => 'CREDIT_CARD',
                            'nextDueDate' => '2025-03-15',
                        ],
                    ],
                ]),
            ]);

            $result = $this->subscriptionResource->list();

            expect($result)->toBeInstanceOf(ListResponse::class)
                ->and($result->hasMore())->toBe(true)
                ->and($result->getTotalCount())->toBe(25)
                ->and($result->count())->toBe(2);

            $subscriptions = $result->getData();
            expect($subscriptions)->toHaveCount(2)
                ->and($subscriptions[0])->toBeInstanceOf(SubscriptionResponse::class)
                ->and($subscriptions[0]->id)->toBe('sub_123')
                ->and($subscriptions[1])->toBeInstanceOf(SubscriptionResponse::class)
                ->and($subscriptions[1]->id)->toBe('sub_456');
        });

    });

    describe('find method', function () {

        it('should find subscription by id', function () {
            $subscriptionId = 'sub_123';

            Http::fake([
                "https://sandbox.asaas.com/api/v3/subscriptions/{$subscriptionId}" => Http::response([
                    'object' => 'subscription',
                    'id' => $subscriptionId,
                    'customer' => 'cus_123',
                    'status' => 'ACTIVE',
                    'value' => 199.90,
                    'cycle' => 'MONTHLY',
                    'description' => 'Assinatura encontrada',
                    'billingType' => 'CREDIT_CARD',
                    'dateCreated' => '2025-01-15 10:30:00',
                ]),
            ]);

            $result = $this->subscriptionResource->find($subscriptionId);

            expect($result)->toBeInstanceOf(SubscriptionResponse::class)
                ->and($result->id)->toBe($subscriptionId)
                ->and($result->value)->toBe(199.90)
                ->and($result->description)->toBe('Assinatura encontrada');
        });

        it('should call correct endpoint', function () {
            Http::fake([
                'https://sandbox.asaas.com/api/v3/subscriptions/sub_test' => Http::response([
                    'object' => 'subscription',
                    'id' => 'sub_test',
                    'status' => 'ACTIVE',
                ]),
            ]);

            $subscriptionId = 'sub_test';
            $result = $this->subscriptionResource->find($subscriptionId);

            expect($result)->toBeInstanceOf(SubscriptionResponse::class);

            Http::assertSent(function ($request) use ($subscriptionId) {
                return $request->method() === 'GET'
                    && $request->url() === "https://sandbox.asaas.com/api/v3/subscriptions/{$subscriptionId}";
            });
        });

    });

    describe('update method', function () {

        it('should update subscription with array data', function () {
            $subscriptionId = 'sub_123';
            $updateData = [
                'description' => 'Assinatura atualizada',
                'value' => 299.90,
            ];

            Http::fake([
                "https://sandbox.asaas.com/api/v3/subscriptions/{$subscriptionId}" => Http::response([
                    'object' => 'subscription',
                    'id' => $subscriptionId,
                    'customer' => 'cus_123',
                    'status' => 'ACTIVE',
                    'value' => 299.90,
                    'description' => 'Assinatura atualizada',
                    'billingType' => 'BOLETO',
                    'dateCreated' => '2025-01-15 10:30:00',
                ]),
            ]);

            $result = $this->subscriptionResource->update($subscriptionId, $updateData);

            expect($result)->toBeInstanceOf(SubscriptionResponse::class)
                ->and($result->description)->toBe('Assinatura atualizada')
                ->and($result->value)->toBe(299.90);
        });

        it('should update subscription with SubscriptionUpdate entity', function () {
            $subscriptionId = 'sub_456';
            $subscriptionUpdate = new SubscriptionUpdate;
            $subscriptionUpdate->description = 'Updated via entity';

            Http::fake([
                "https://sandbox.asaas.com/api/v3/subscriptions/{$subscriptionId}" => Http::response([
                    'object' => 'subscription',
                    'id' => $subscriptionId,
                    'description' => 'Updated via entity',
                    'status' => 'ACTIVE',
                ]),
            ]);

            $result = $this->subscriptionResource->update($subscriptionId, $subscriptionUpdate);

            expect($result)->toBeInstanceOf(SubscriptionResponse::class)
                ->and($result->description)->toBe('Updated via entity');
        });

    });

    describe('delete method', function () {

        it('should delete subscription and return Deleted object', function () {
            $subscriptionId = 'sub_123';

            Http::fake([
                "https://sandbox.asaas.com/api/v3/subscriptions/{$subscriptionId}" => Http::response([
                    'id' => $subscriptionId,
                    'deleted' => true,
                ]),
            ]);

            $result = $this->subscriptionResource->delete($subscriptionId);

            expect($result)->toBeInstanceOf(Deleted::class)
                ->and($result->id)->toBe($subscriptionId)
                ->and($result->deleted)->toBe(true);
        });

        it('should call DELETE method', function () {
            Http::fake([
                'https://sandbox.asaas.com/api/v3/subscriptions/sub_test' => Http::response([
                    'id' => 'sub_test',
                    'deleted' => true,
                ]),
            ]);

            $subscriptionId = 'sub_test';
            $result = $this->subscriptionResource->delete($subscriptionId);

            expect($result)->toBeInstanceOf(Deleted::class);

            Http::assertSent(function ($request) use ($subscriptionId) {
                return $request->method() === 'DELETE'
                    && $request->url() === "https://sandbox.asaas.com/api/v3/subscriptions/{$subscriptionId}";
            });
        });

    });

    describe('updateCreditCard method', function () {

        it('should update credit card with array data', function () {
            $subscriptionId = 'sub_123';
            $creditCardData = [
                'holderName' => 'João Silva',
                'number' => '4111111111111111',
                'expiryMonth' => '12',
                'expiryYear' => '2028',
                'ccv' => '123',
            ];

            Http::fake([
                "https://sandbox.asaas.com/api/v3/subscriptions/{$subscriptionId}/creditCard" => Http::response([
                    'object' => 'subscription',
                    'id' => $subscriptionId,
                    'customer' => 'cus_123',
                    'status' => 'ACTIVE',
                    'billingType' => 'CREDIT_CARD',
                ]),
            ]);

            $result = $this->subscriptionResource->updateCreditCard($subscriptionId, $creditCardData);

            expect($result)->toBeInstanceOf(SubscriptionResponse::class)
                ->and($result->id)->toBe($subscriptionId)
                ->and($result->billingType->value)->toBe('CREDIT_CARD');
        });

        it('should update credit card with SubscriptionUpdateCreditCard entity', function () {
            $subscriptionId = 'sub_456';
            $creditCard = new SubscriptionUpdateCreditCard;

            Http::fake([
                "https://sandbox.asaas.com/api/v3/subscriptions/{$subscriptionId}/creditCard" => Http::response([
                    'object' => 'subscription',
                    'id' => $subscriptionId,
                    'status' => 'ACTIVE',
                    'billingType' => 'CREDIT_CARD',
                ]),
            ]);

            $result = $this->subscriptionResource->updateCreditCard($subscriptionId, $creditCard);

            expect($result)->toBeInstanceOf(SubscriptionResponse::class)
                ->and($result->id)->toBe($subscriptionId);

            Http::assertSent(function ($request) use ($subscriptionId) {
                return $request->method() === 'PUT'
                    && $request->url() === "https://sandbox.asaas.com/api/v3/subscriptions/{$subscriptionId}/creditCard";
            });
        });

    });

    describe('listPayments method', function () {

        it('should list subscription payments', function () {
            $subscriptionId = 'sub_123';

            Http::fake([
                "https://sandbox.asaas.com/api/v3/subscriptions/{$subscriptionId}/payments" => Http::response([
                    'object' => 'list',
                    'hasMore' => false,
                    'totalCount' => 2,
                    'limit' => 20,
                    'offset' => 0,
                    'data' => [
                        [
                            'object' => 'payment',
                            'id' => 'pay_123',
                            'subscription' => $subscriptionId,
                            'status' => 'RECEIVED',
                            'value' => 99.90,
                            'dueDate' => '2025-02-15',
                        ],
                        [
                            'object' => 'payment',
                            'id' => 'pay_456',
                            'subscription' => $subscriptionId,
                            'status' => 'PENDING',
                            'value' => 99.90,
                            'dueDate' => '2025-03-15',
                        ],
                    ],
                ]),
            ]);

            $result = $this->subscriptionResource->listPayments($subscriptionId);

            expect($result)->toBeInstanceOf(ListResponse::class)
                ->and($result->getTotalCount())->toBe(2)
                ->and($result->count())->toBe(2);

            Http::assertSent(function ($request) use ($subscriptionId) {
                return $request->method() === 'GET'
                    && $request->url() === "https://sandbox.asaas.com/api/v3/subscriptions/{$subscriptionId}/payments";
            });
        });

        it('should list subscription payments with status filter', function () {
            $subscriptionId = 'sub_123';
            $params = ['status' => 'RECEIVED'];

            Http::fake([
                "https://sandbox.asaas.com/api/v3/subscriptions/{$subscriptionId}/payments*" => Http::response([
                    'object' => 'list',
                    'hasMore' => false,
                    'totalCount' => 1,
                    'limit' => 20,
                    'offset' => 0,
                    'data' => [
                        [
                            'object' => 'payment',
                            'id' => 'pay_123',
                            'subscription' => $subscriptionId,
                            'status' => 'RECEIVED',
                            'value' => 99.90,
                        ],
                    ],
                ]),
            ]);

            $result = $this->subscriptionResource->listPayments($subscriptionId, $params);

            expect($result)->toBeInstanceOf(ListResponse::class)
                ->and($result->getTotalCount())->toBe(1);

            Http::assertSent(function ($request) use ($subscriptionId) {
                return $request->method() === 'GET'
                    && str_contains($request->url(), "subscriptions/{$subscriptionId}/payments")
                    && str_contains($request->url(), 'status=RECEIVED');
            });
        });

    });

});
