<?php

use Illuminate\Support\Facades\Http;
use Leopaulo88\Asaas\Entities\CreditCardToken\CreditCardTokenCreate;
use Leopaulo88\Asaas\Entities\CreditCardToken\CreditCardTokenResponse;
use Leopaulo88\Asaas\Resources\CreditCardResource;
use Leopaulo88\Asaas\Support\AsaasClient;

beforeEach(function () {
    $this->client = new AsaasClient('test_api_key', 'sandbox');
    $this->creditCardResource = new CreditCardResource($this->client);
});

describe('CreditCardResource', function () {

    describe('tokenize method', function () {

        it('should tokenize credit card with array data', function () {
            $creditCardData = [
                'customer' => 'cus_123',
                'creditCard' => [
                    'holderName' => 'João Silva',
                    'number' => '4111111111111111',
                    'expiryMonth' => '12',
                    'expiryYear' => '2028',
                    'ccv' => '123'
                ],
                'creditCardHolderInfo' => [
                    'name' => 'João Silva',
                    'email' => 'joao@teste.com',
                    'cpfCnpj' => '12345678901',
                    'postalCode' => '12345678',
                    'addressNumber' => '123',
                    'phone' => '11999999999'
                ],
                'remoteIp' => '192.168.1.1'
            ];

            Http::fake([
                'https://sandbox.asaas.com/api/v3/creditCard/tokenizeCreditCard' => Http::response([
                    'creditCardNumber' => '****1111',
                    'creditCardBrand' => 'VISA',
                    'creditCardToken' => 'cc_token_123456789'
                ])
            ]);

            $result = $this->creditCardResource->tokenize($creditCardData);

            expect($result)->toBeInstanceOf(CreditCardTokenResponse::class)
                ->and($result->creditCardNumber)->toBe('****1111')
                ->and($result->creditCardBrand->value)->toBe('VISA')
                ->and($result->creditCardToken)->toBe('cc_token_123456789');

            Http::assertSent(function ($request) {
                return $request->method() === 'POST'
                    && str_contains($request->url(), '/creditCard/tokenizeCreditCard')
                    && $request->header('access_token')[0] === 'test_api_key';
            });
        });

        it('should tokenize credit card with CreditCardTokenCreate entity', function () {
            $creditCardTokenCreate = new CreditCardTokenCreate();
            $creditCardTokenCreate->customer('cus_456');

            Http::fake([
                'https://sandbox.asaas.com/api/v3/creditCard/tokenizeCreditCard' => Http::response([
                    'creditCardNumber' => '****4242',
                    'creditCardBrand' => 'MASTERCARD',
                    'creditCardToken' => 'cc_token_987654321'
                ])
            ]);

            $result = $this->creditCardResource->tokenize($creditCardTokenCreate);

            expect($result)->toBeInstanceOf(CreditCardTokenResponse::class)
                ->and($result->creditCardNumber)->toBe('****4242')
                ->and($result->creditCardBrand->value)->toBe('MASTERCARD')
                ->and($result->creditCardToken)->toBe('cc_token_987654321');
        });

        it('should send correct request data', function () {
            Http::fake([
                'https://sandbox.asaas.com/api/v3/creditCard/tokenizeCreditCard' => Http::response([
                    'creditCardNumber' => '****1111',
                    'creditCardBrand' => 'VISA',
                    'creditCardToken' => 'cc_token_test'
                ])
            ]);

            $creditCardData = [
                'customer' => 'cus_test',
                'creditCard' => [
                    'holderName' => 'Test User',
                    'number' => '4111111111111111',
                    'expiryMonth' => '01',
                    'expiryYear' => '2030',
                    'ccv' => '456'
                ],
                'remoteIp' => '127.0.0.1'
            ];

            $this->creditCardResource->tokenize($creditCardData);

            Http::assertSent(function ($request) use ($creditCardData) {
                $body = json_decode($request->body(), true);

                return $request->method() === 'POST'
                    && $request->url() === 'https://sandbox.asaas.com/api/v3/creditCard/tokenizeCreditCard'
                    && $body['customer'] === $creditCardData['customer']
                    && $body['remoteIp'] === $creditCardData['remoteIp']
                    && isset($body['creditCard']);
            });
        });

        it('should send correct headers', function () {
            Http::fake([
                'https://sandbox.asaas.com/api/v3/creditCard/tokenizeCreditCard' => Http::response([
                    'creditCardNumber' => '****1111',
                    'creditCardBrand' => 'VISA',
                    'creditCardToken' => 'cc_token_headers_test'
                ])
            ]);

            $creditCardData = [
                'customer' => 'cus_headers_test',
                'creditCard' => [
                    'holderName' => 'Header Test',
                    'number' => '4111111111111111',
                    'expiryMonth' => '06',
                    'expiryYear' => '2029',
                    'ccv' => '789'
                ]
            ];

            $result = $this->creditCardResource->tokenize($creditCardData);

            expect($result)->toBeInstanceOf(CreditCardTokenResponse::class);

            Http::assertSent(function ($request) {
                return $request->method() === 'POST'
                    && str_contains($request->url(), '/creditCard/tokenizeCreditCard')
                    && $request->header('access_token')[0] === 'test_api_key'
                    && $request->header('Accept')[0] === 'application/json'
                    && $request->header('Content-Type')[0] === 'application/json';
            });
        });

        it('should handle minimal credit card data', function () {
            Http::fake([
                'https://sandbox.asaas.com/api/v3/creditCard/tokenizeCreditCard' => Http::response([
                    'creditCardNumber' => '****5555',
                    'creditCardBrand' => 'MASTERCARD',
                    'creditCardToken' => 'cc_token_minimal'
                ])
            ]);

            $minimalData = [
                'customer' => 'cus_minimal',
                'creditCard' => [
                    'holderName' => 'Minimal User',
                    'number' => '5555555555554444',
                    'expiryMonth' => '03',
                    'expiryYear' => '2027',
                    'ccv' => '321'
                ]
            ];

            $result = $this->creditCardResource->tokenize($minimalData);

            expect($result)->toBeInstanceOf(CreditCardTokenResponse::class)
                ->and($result->creditCardToken)->toBe('cc_token_minimal');
        });

        it('should handle VISA credit card', function () {
            Http::fake([
                'https://sandbox.asaas.com/api/v3/creditCard/tokenizeCreditCard' => Http::response([
                    'creditCardNumber' => '****1111',
                    'creditCardBrand' => 'VISA',
                    'creditCardToken' => 'cc_token_visa'
                ])
            ]);

            $requestData = [
                'customer' => 'cus_visa_test',
                'creditCard' => [
                    'holderName' => 'VISA Test',
                    'number' => '4111111111111111',
                    'expiryMonth' => '12',
                    'expiryYear' => '2025',
                    'ccv' => '123'
                ]
            ];

            $result = $this->creditCardResource->tokenize($requestData);

            expect($result)->toBeInstanceOf(CreditCardTokenResponse::class)
                ->and($result->creditCardBrand->value)->toBe('VISA')
                ->and($result->creditCardToken)->toBe('cc_token_visa');
        });

        it('should handle MASTERCARD credit card', function () {
            Http::fake([
                'https://sandbox.asaas.com/api/v3/creditCard/tokenizeCreditCard' => Http::response([
                    'creditCardNumber' => '****4444',
                    'creditCardBrand' => 'MASTERCARD',
                    'creditCardToken' => 'cc_token_mastercard'
                ])
            ]);

            $requestData = [
                'customer' => 'cus_mastercard_test',
                'creditCard' => [
                    'holderName' => 'MASTERCARD Test',
                    'number' => '5555555555554444',
                    'expiryMonth' => '12',
                    'expiryYear' => '2025',
                    'ccv' => '123'
                ]
            ];

            $result = $this->creditCardResource->tokenize($requestData);

            expect($result)->toBeInstanceOf(CreditCardTokenResponse::class)
                ->and($result->creditCardBrand->value)->toBe('MASTERCARD')
                ->and($result->creditCardToken)->toBe('cc_token_mastercard');
        });

        it('should handle AMEX credit card', function () {
            Http::fake([
                'https://sandbox.asaas.com/api/v3/creditCard/tokenizeCreditCard' => Http::response([
                    'creditCardNumber' => '****0005',
                    'creditCardBrand' => 'AMEX',
                    'creditCardToken' => 'cc_token_amex'
                ])
            ]);

            $requestData = [
                'customer' => 'cus_amex_test',
                'creditCard' => [
                    'holderName' => 'AMEX Test',
                    'number' => '378282246310005',
                    'expiryMonth' => '12',
                    'expiryYear' => '2025',
                    'ccv' => '123'
                ]
            ];

            $result = $this->creditCardResource->tokenize($requestData);

            expect($result)->toBeInstanceOf(CreditCardTokenResponse::class)
                ->and($result->creditCardBrand->value)->toBe('AMEX')
                ->and($result->creditCardToken)->toBe('cc_token_amex');
        });

        it('should call correct API endpoint', function () {
            Http::fake([
                'https://sandbox.asaas.com/api/v3/creditCard/tokenizeCreditCard' => Http::response([
                    'creditCardNumber' => '****1111',
                    'creditCardBrand' => 'VISA',
                    'creditCardToken' => 'cc_token_endpoint_test'
                ])
            ]);

            $creditCardData = [
                'customer' => 'cus_endpoint_test',
                'creditCard' => [
                    'holderName' => 'Endpoint Test',
                    'number' => '4111111111111111',
                    'expiryMonth' => '11',
                    'expiryYear' => '2026',
                    'ccv' => '987'
                ]
            ];

            $this->creditCardResource->tokenize($creditCardData);

            Http::assertSent(function ($request) {
                return $request->method() === 'POST'
                    && $request->url() === 'https://sandbox.asaas.com/api/v3/creditCard/tokenizeCreditCard';
            });
        });

    });

});
