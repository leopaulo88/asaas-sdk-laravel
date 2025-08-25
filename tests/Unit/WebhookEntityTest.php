<?php

use Leopaulo88\Asaas\Entities\Webhook\WebhookCreate;
use Leopaulo88\Asaas\Entities\Webhook\WebhookUpdate;

it('can create webhook with fluent interface', function () {
    $webhook = WebhookCreate::make()
        ->name('Test Webhook')
        ->url('https://example.com/webhook')
        ->email('test@example.com')
        ->enabled(true)
        ->interrupted(false)
        ->apiVersion(3)
        ->authToken('test_token_123')
        ->sendType('SEQUENTIALLY')
        ->events([
            'PAYMENT_CREATED',
            'PAYMENT_CONFIRMED',
            'PAYMENT_RECEIVED',
        ]);

    expect($webhook->name)->toBe('Test Webhook')
        ->and($webhook->url)->toBe('https://example.com/webhook')
        ->and($webhook->email)->toBe('test@example.com')
        ->and($webhook->enabled)->toBeTrue()
        ->and($webhook->interrupted)->toBeFalse()
        ->and($webhook->apiVersion)->toBe(3)
        ->and($webhook->authToken)->toBe('test_token_123')
        ->and($webhook->sendType)->toBe('SEQUENTIALLY')
        ->and($webhook->events)->toHaveCount(3)
        ->and($webhook->events[0])->toBe('PAYMENT_CREATED');
});

it('can create webhook with constructor parameters', function () {
    $webhook = new WebhookCreate(
        name: 'Constructor Webhook',
        url: 'https://test.com/hook',
        email: 'admin@test.com',
        enabled: true,
        interrupted: false,
        apiVersion: 3,
        authToken: 'constructor_token',
        sendType: 'NON_SEQUENTIALLY',
        events: [
            'SUBSCRIPTION_CREATED',
            'SUBSCRIPTION_UPDATED',
        ]
    );

    expect($webhook->name)->toBe('Constructor Webhook')
        ->and($webhook->url)->toBe('https://test.com/hook')
        ->and($webhook->email)->toBe('admin@test.com')
        ->and($webhook->enabled)->toBeTrue()
        ->and($webhook->sendType)->toBe('NON_SEQUENTIALLY')
        ->and($webhook->events)->toHaveCount(2);
});

it('can create webhook from array', function () {
    $webhookData = [
        'name' => 'Array Webhook',
        'url' => 'https://array.test/webhook',
        'email' => 'array@test.com',
        'enabled' => true,
        'sendType' => 'SEQUENTIALLY',
        'events' => ['PAYMENT_CREATED', 'PAYMENT_CONFIRMED'],
    ];

    $webhook = WebhookCreate::fromArray($webhookData);

    expect($webhook->name)->toBe('Array Webhook')
        ->and($webhook->url)->toBe('https://array.test/webhook')
        ->and($webhook->email)->toBe('array@test.com')
        ->and($webhook->enabled)->toBeTrue()
        ->and($webhook->sendType)->toBe('SEQUENTIALLY')
        ->and($webhook->events)->toHaveCount(2);
});

it('can create webhook with minimal data', function () {
    $webhook = WebhookCreate::make()
        ->name('Minimal Webhook')
        ->url('https://minimal.com/webhook')
        ->events(['PAYMENT_CREATED']);

    expect($webhook->name)->toBe('Minimal Webhook')
        ->and($webhook->url)->toBe('https://minimal.com/webhook')
        ->and($webhook->events)->toHaveCount(1)
        ->and($webhook->email)->toBeNull()
        ->and($webhook->enabled)->toBeNull()
        ->and($webhook->apiVersion)->toBeNull();
});

it('can create webhook update entity', function () {
    $update = WebhookUpdate::make()
        ->name('Updated Webhook')
        ->enabled(false)
        ->events(['PAYMENT_CONFIRMED', 'PAYMENT_RECEIVED']);

    expect($update->name)->toBe('Updated Webhook')
        ->and($update->enabled)->toBeFalse()
        ->and($update->events)->toHaveCount(2)
        ->and($update->events)->toContain('PAYMENT_CONFIRMED')
        ->and($update->events)->toContain('PAYMENT_RECEIVED');
});

it('can set webhook send type with string values', function () {
    $sequentialWebhook = WebhookCreate::make()->sendType('SEQUENTIALLY');
    $nonSequentialWebhook = WebhookCreate::make()->sendType('NON_SEQUENTIALLY');

    expect($sequentialWebhook->sendType)->toBe('SEQUENTIALLY')
        ->and($nonSequentialWebhook->sendType)->toBe('NON_SEQUENTIALLY');
});

it('can set webhook events with payment event strings', function () {
    $webhook = WebhookCreate::make()->events([
        'PAYMENT_CREATED',
        'PAYMENT_UPDATED',
        'PAYMENT_CONFIRMED',
        'PAYMENT_RECEIVED',
    ]);

    expect($webhook->events)->toHaveCount(4)
        ->and($webhook->events)->toContain('PAYMENT_CREATED')
        ->and($webhook->events)->toContain('PAYMENT_CONFIRMED');
});

it('can set webhook events with subscription event strings', function () {
    $webhook = WebhookCreate::make()->events([
        'SUBSCRIPTION_CREATED',
        'SUBSCRIPTION_UPDATED',
        'SUBSCRIPTION_DELETED',
    ]);

    expect($webhook->events)->toHaveCount(3)
        ->and($webhook->events)->toContain('SUBSCRIPTION_CREATED')
        ->and($webhook->events)->toContain('SUBSCRIPTION_DELETED');
});

it('can set webhook events with transfer event strings', function () {
    $webhook = WebhookCreate::make()->events([
        'TRANSFER_CREATED',
        'TRANSFER_DONE',
        'TRANSFER_FAILED',
    ]);

    expect($webhook->events)->toHaveCount(3)
        ->and($webhook->events)->toContain('TRANSFER_CREATED')
        ->and($webhook->events)->toContain('TRANSFER_DONE');
});

it('can chain webhook methods', function () {
    $webhook = WebhookCreate::make()
        ->name('Chained Webhook')
        ->url('https://chain.test/webhook')
        ->email('test@chain.com')
        ->enabled(true)
        ->authToken('secret-token')
        ->apiVersion(3)
        ->sendType('SEQUENTIALLY')
        ->events(['PAYMENT_CREATED', 'TRANSFER_DONE']);

    expect($webhook->name)->toBe('Chained Webhook')
        ->and($webhook->url)->toBe('https://chain.test/webhook')
        ->and($webhook->email)->toBe('test@chain.com')
        ->and($webhook->enabled)->toBeTrue()
        ->and($webhook->authToken)->toBe('secret-token')
        ->and($webhook->apiVersion)->toBe(3)
        ->and($webhook->sendType)->toBe('SEQUENTIALLY')
        ->and($webhook->events)->toHaveCount(2);
});

it('can handle all webhook properties', function () {
    $webhook = WebhookCreate::make()
        ->name('Complete Webhook')
        ->url('https://complete.test/webhook')
        ->email('complete@test.com')
        ->enabled(false)
        ->interrupted(true)
        ->apiVersion(2)
        ->authToken('complete-token')
        ->sendType('NON_SEQUENTIALLY')
        ->events([
            'PAYMENT_CREATED',
            'PAYMENT_CONFIRMED',
            'SUBSCRIPTION_CREATED',
            'TRANSFER_DONE',
        ]);

    expect($webhook->name)->toBe('Complete Webhook')
        ->and($webhook->url)->toBe('https://complete.test/webhook')
        ->and($webhook->email)->toBe('complete@test.com')
        ->and($webhook->enabled)->toBeFalse()
        ->and($webhook->interrupted)->toBeTrue()
        ->and($webhook->apiVersion)->toBe(2)
        ->and($webhook->authToken)->toBe('complete-token')
        ->and($webhook->sendType)->toBe('NON_SEQUENTIALLY')
        ->and($webhook->events)->toHaveCount(4);
});

it('webhook accepts valid event strings', function () {
    $validEvents = [
        'PAYMENT_CREATED',
        'PAYMENT_UPDATED',
        'PAYMENT_CONFIRMED',
        'PAYMENT_RECEIVED',
        'PAYMENT_CREDIT_CARD_CAPTURE_REFUSED',
        'PAYMENT_AWAITING_CHARGEBACK_DEBIT',
        'PAYMENT_DUNNING_RECEIVED',
        'PAYMENT_DUNNING_REQUESTED',
        'PAYMENT_BANK_SLIP_VIEWED',
        'PAYMENT_CHECKOUT_VIEWED',
        'SUBSCRIPTION_CREATED',
        'SUBSCRIPTION_UPDATED',
        'SUBSCRIPTION_DELETED',
        'TRANSFER_CREATED',
        'TRANSFER_PENDING',
        'TRANSFER_IN_BANK_PROCESSING',
        'TRANSFER_DONE',
        'TRANSFER_FAILED',
        'TRANSFER_CANCELLED',
        'ACCOUNT_STATUS_UPDATED',
    ];

    $webhook = WebhookCreate::make()->events($validEvents);

    expect($webhook->events)->toHaveCount(20)
        ->and($webhook->events)->toContain('PAYMENT_CREATED')
        ->and($webhook->events)->toContain('SUBSCRIPTION_CREATED')
        ->and($webhook->events)->toContain('TRANSFER_CREATED')
        ->and($webhook->events)->toContain('ACCOUNT_STATUS_UPDATED');
});

it('webhook send type accepts valid string values', function () {
    $webhook1 = WebhookCreate::make()->sendType('SEQUENTIALLY');
    $webhook2 = WebhookCreate::make()->sendType('NON_SEQUENTIALLY');

    expect($webhook1->sendType)->toBe('SEQUENTIALLY')
        ->and($webhook2->sendType)->toBe('NON_SEQUENTIALLY');
});

it('can create webhook for specific use cases', function () {
    // Payment monitoring webhook
    $paymentWebhook = WebhookCreate::make()
        ->name('Payment Monitor')
        ->url('https://api.myapp.com/webhooks/payments')
        ->sendType('SEQUENTIALLY')
        ->events(['PAYMENT_CONFIRMED', 'PAYMENT_RECEIVED']);

    expect($paymentWebhook->events)->toHaveCount(2)
        ->and($paymentWebhook->sendType)->toBe('SEQUENTIALLY');

    // Subscription management webhook
    $subscriptionWebhook = WebhookCreate::make()
        ->name('Subscription Manager')
        ->url('https://api.myapp.com/webhooks/subscriptions')
        ->sendType('NON_SEQUENTIALLY')
        ->events(['SUBSCRIPTION_CREATED', 'SUBSCRIPTION_UPDATED', 'SUBSCRIPTION_DELETED']);

    expect($subscriptionWebhook->events)->toHaveCount(3)
        ->and($subscriptionWebhook->sendType)->toBe('NON_SEQUENTIALLY');
});

it('can create webhook update with partial data', function () {
    $update = WebhookUpdate::make()
        ->name('Partially Updated Webhook')
        ->enabled(true);

    expect($update->name)->toBe('Partially Updated Webhook')
        ->and($update->enabled)->toBeTrue()
        ->and($update->url)->toBeNull()
        ->and($update->events)->toBeNull();
});

it('webhook update supports all webhook properties', function () {
    $update = WebhookUpdate::make()
        ->name('Full Update')
        ->url('https://updated.com/webhook')
        ->enabled(false)
        ->interrupted(false)
        ->authToken('new-token')
        ->sendType('SEQUENTIALLY')
        ->events(['PAYMENT_CONFIRMED']);

    expect($update->name)->toBe('Full Update')
        ->and($update->url)->toBe('https://updated.com/webhook')
        ->and($update->enabled)->toBeFalse()
        ->and($update->interrupted)->toBeFalse()
        ->and($update->authToken)->toBe('new-token')
        ->and($update->sendType)->toBe('SEQUENTIALLY')
        ->and($update->events)->toHaveCount(1);
});
