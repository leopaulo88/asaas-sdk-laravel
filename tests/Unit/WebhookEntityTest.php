<?php

use Leopaulo88\Asaas\Entities\Common\Webhook;
use Leopaulo88\Asaas\Enums\WebhookEvent;
use Leopaulo88\Asaas\Enums\WebhookSendType;

it('can create webhook with fluent interface', function () {
    $webhook = (new Webhook)
        ->name('Test Webhook')
        ->url('https://example.com/webhook')
        ->email('test@example.com')
        ->enabled(true)
        ->interrupted(false)
        ->apiVersion(3)
        ->authToken('test_token_123')
        ->sendType(WebhookSendType::SEQUENTIALLY)
        ->events([
            WebhookEvent::PAYMENT_CREATED,
            WebhookEvent::PAYMENT_CONFIRMED,
            WebhookEvent::PAYMENT_RECEIVED,
        ]);

    expect($webhook->name)->toBe('Test Webhook')
        ->and($webhook->url)->toBe('https://example.com/webhook')
        ->and($webhook->email)->toBe('test@example.com')
        ->and($webhook->enabled)->toBeTrue()
        ->and($webhook->interrupted)->toBeFalse()
        ->and($webhook->apiVersion)->toBe(3)
        ->and($webhook->authToken)->toBe('test_token_123')
        ->and($webhook->sendType)->toBe(WebhookSendType::SEQUENTIALLY)
        ->and($webhook->events)->toHaveCount(3)
        ->and($webhook->events[0])->toBe(WebhookEvent::PAYMENT_CREATED);
});

it('can create webhook with constructor parameters', function () {
    $webhook = new Webhook(
        name: 'Constructor Webhook',
        url: 'https://test.com/hook',
        email: 'admin@test.com',
        enabled: true,
        interrupted: false,
        apiVersion: 3,
        authToken: 'constructor_token',
        sendType: WebhookSendType::NON_SEQUENTIALLY,
        events: [
            WebhookEvent::SUBSCRIPTION_CREATED,
            WebhookEvent::SUBSCRIPTION_UPDATED,
        ]
    );

    expect($webhook->name)->toBe('Constructor Webhook')
        ->and($webhook->url)->toBe('https://test.com/hook')
        ->and($webhook->email)->toBe('admin@test.com')
        ->and($webhook->enabled)->toBeTrue()
        ->and($webhook->sendType)->toBe(WebhookSendType::NON_SEQUENTIALLY)
        ->and($webhook->events)->toHaveCount(2);
});

it('can create webhook with minimal data', function () {
    $webhook = (new Webhook)
        ->name('Minimal Webhook')
        ->url('https://minimal.com/webhook')
        ->events([WebhookEvent::PAYMENT_CREATED]);

    expect($webhook->name)->toBe('Minimal Webhook')
        ->and($webhook->url)->toBe('https://minimal.com/webhook')
        ->and($webhook->events)->toHaveCount(1)
        ->and($webhook->email)->toBeNull()
        ->and($webhook->enabled)->toBeNull()
        ->and($webhook->apiVersion)->toBeNull();
});

it('webhook send type enum has correct values', function () {
    expect(WebhookSendType::SEQUENTIALLY->value)->toBe('SEQUENTIALLY')
        ->and(WebhookSendType::NON_SEQUENTIALLY->value)->toBe('NON_SEQUENTIALLY');
});

it('webhook events enum has payment events', function () {
    expect(WebhookEvent::PAYMENT_CREATED->value)->toBe('PAYMENT_CREATED')
        ->and(WebhookEvent::PAYMENT_CONFIRMED->value)->toBe('PAYMENT_CONFIRMED')
        ->and(WebhookEvent::PAYMENT_RECEIVED->value)->toBe('PAYMENT_RECEIVED')
        ->and(WebhookEvent::PAYMENT_OVERDUE->value)->toBe('PAYMENT_OVERDUE')
        ->and(WebhookEvent::PAYMENT_REFUNDED->value)->toBe('PAYMENT_REFUNDED');
});

it('webhook events enum has subscription events', function () {
    expect(WebhookEvent::SUBSCRIPTION_CREATED->value)->toBe('SUBSCRIPTION_CREATED')
        ->and(WebhookEvent::SUBSCRIPTION_UPDATED->value)->toBe('SUBSCRIPTION_UPDATED')
        ->and(WebhookEvent::SUBSCRIPTION_INACTIVATED->value)->toBe('SUBSCRIPTION_INACTIVATED')
        ->and(WebhookEvent::SUBSCRIPTION_DELETED->value)->toBe('SUBSCRIPTION_DELETED');
});

it('webhook events enum has transfer events', function () {
    expect(WebhookEvent::TRANSFER_CREATED->value)->toBe('TRANSFER_CREATED')
        ->and(WebhookEvent::TRANSFER_PENDING->value)->toBe('TRANSFER_PENDING')
        ->and(WebhookEvent::TRANSFER_IN_BANK_PROCESSING->value)->toBe('TRANSFER_IN_BANK_PROCESSING')
        ->and(WebhookEvent::TRANSFER_BLOCKED->value)->toBe('TRANSFER_BLOCKED');
});

it('can create webhook for all event types', function () {
    $allEvents = [
        // Payment events
        WebhookEvent::PAYMENT_CREATED,
        WebhookEvent::PAYMENT_CONFIRMED,
        WebhookEvent::PAYMENT_RECEIVED,
        WebhookEvent::PAYMENT_OVERDUE,
        WebhookEvent::PAYMENT_REFUNDED,

        // Subscription events
        WebhookEvent::SUBSCRIPTION_CREATED,
        WebhookEvent::SUBSCRIPTION_UPDATED,
        WebhookEvent::SUBSCRIPTION_INACTIVATED,

        // Transfer events
        WebhookEvent::TRANSFER_CREATED,
        WebhookEvent::TRANSFER_PENDING,

        // Invoice events
        WebhookEvent::INVOICE_CREATED,
        WebhookEvent::INVOICE_UPDATED,
        WebhookEvent::INVOICE_AUTHORIZED,
        WebhookEvent::INVOICE_CANCELED,
    ];

    $webhook = (new Webhook)
        ->name('All Events Webhook')
        ->url('https://allev.com/webhook')
        ->enabled(true)
        ->sendType(WebhookSendType::SEQUENTIALLY)
        ->events($allEvents);

    expect($webhook->events)->toHaveCount(count($allEvents))
        ->and($webhook->events)->toContain(WebhookEvent::PAYMENT_CREATED)
        ->and($webhook->events)->toContain(WebhookEvent::SUBSCRIPTION_CREATED)
        ->and($webhook->events)->toContain(WebhookEvent::TRANSFER_CREATED)
        ->and($webhook->events)->toContain(WebhookEvent::INVOICE_CREATED);
});
