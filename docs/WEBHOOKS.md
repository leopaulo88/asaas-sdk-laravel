# Webhook Resource

Webhooks allow you to receive real-time notifications about events in your Asaas account. When specific events occur (like payment confirmations, subscription updates, etc.), Asaas will send HTTP POST requests to your configured webhook URLs.

## Table of Contents

- [Creating Webhooks](#creating-webhooks)
- [Listing Webhooks](#listing-webhooks)
- [Finding a Webhook](#finding-a-webhook)
- [Updating Webhooks](#updating-webhooks)
- [Removing Webhooks](#removing-webhooks)
- [Webhook Events](#webhook-events)
- [Send Types](#send-types)
- [Webhook Security](#webhook-security)
- [Entity Reference](#entity-reference)
- [Best Practices](#best-practices)

## Creating Webhooks

### Basic Webhook

```php
use Leopaulo88\Asaas\Facades\Asaas;

$webhook = Asaas::webhooks()->create([
    'name' => 'Payment Notifications',
    'url' => 'https://myapp.com/webhooks/asaas',
    'email' => 'admin@myapp.com',
    'enabled' => true,
    'sendType' => 'SEQUENTIALLY',
    'events' => [
        'PAYMENT_CREATED',
        'PAYMENT_CONFIRMED',
        'PAYMENT_RECEIVED'
    ]
]);
```

### Using Webhook Entity

```php
use Leopaulo88\Asaas\Entities\Webhook\WebhookCreate;

$webhook = WebhookCreate::make()
    ->name('Subscription Webhook')
    ->url('https://api.myapp.com/asaas/webhooks')
    ->email('notifications@myapp.com')
    ->enabled(true)
    ->sendType('NON_SEQUENTIALLY')
    ->apiVersion(3)
    ->events([
        'SUBSCRIPTION_CREATED',
        'SUBSCRIPTION_UPDATED',
        'SUBSCRIPTION_DELETED'
    ]);

$result = Asaas::webhooks()->create($webhook);
```

## Listing Webhooks

```php
// List all webhooks
$webhooks = Asaas::webhooks()->list();

// List with pagination
$webhooks = Asaas::webhooks()->list([
    'offset' => 0,
    'limit' => 20
]);

foreach ($webhooks->data as $webhook) {
    echo "Webhook: {$webhook->name} - {$webhook->url}\n";
    echo "Status: " . ($webhook->enabled ? 'Enabled' : 'Disabled') . "\n";
    echo "Events: " . implode(', ', $webhook->events) . "\n";
}
```

## Finding a Webhook

```php
$webhook = Asaas::webhooks()->find('webhook_123456789');

echo "Name: {$webhook->name}\n";
echo "URL: {$webhook->url}\n";
echo "Enabled: " . ($webhook->enabled ? 'Yes' : 'No') . "\n";
echo "Send Type: {$webhook->sendType}\n";
echo "Events: " . implode(', ', $webhook->events) . "\n";
```

## Updating Webhooks

```php
use Leopaulo88\Asaas\Entities\Webhook\WebhookUpdate;

// Update using array
$webhook = Asaas::webhooks()->update('webhook_123456789', [
    'name' => 'Updated Webhook Name',
    'enabled' => false,
    'events' => ['PAYMENT_CONFIRMED', 'PAYMENT_RECEIVED']
]);

// Update using entity
$updateData = WebhookUpdate::make()
    ->name('New Webhook Name')
    ->url('https://newdomain.com/webhook')
    ->enabled(true);

$webhook = Asaas::webhooks()->update('webhook_123456789', $updateData);
```

## Removing Webhooks

```php
$deleted = Asaas::webhooks()->remove('webhook_123456789');

if ($deleted) {
    echo "Webhook successfully removed\n";
}
```

## Webhook Events

### Payment Events

```php
$paymentEvents = [
    'PAYMENT_CREATED',           // Payment was created
    'PAYMENT_UPDATED',           // Payment was updated
    'PAYMENT_CONFIRMED',         // Payment was confirmed
    'PAYMENT_RECEIVED',          // Payment was received
    'PAYMENT_CREDIT_CARD_CAPTURE_REFUSED',  // Credit card capture was refused
    'PAYMENT_AWAITING_CHARGEBACK_DEBIT',    // Payment is awaiting chargeback debit
    'PAYMENT_DUNNING_RECEIVED',  // Dunning was received for payment
    'PAYMENT_DUNNING_REQUESTED', // Dunning was requested for payment
    'PAYMENT_BANK_SLIP_VIEWED',  // Bank slip was viewed
    'PAYMENT_CHECKOUT_VIEWED',   // Checkout page was viewed
];
```

### Subscription Events

```php
$subscriptionEvents = [
    'SUBSCRIPTION_CREATED',      // Subscription was created
    'SUBSCRIPTION_UPDATED',      // Subscription was updated
    'SUBSCRIPTION_DELETED',      // Subscription was deleted
];
```

### Transfer Events

```php
$transferEvents = [
    'TRANSFER_CREATED',          // Transfer was created
    'TRANSFER_PENDING',          // Transfer is pending
    'TRANSFER_IN_BANK_PROCESSING', // Transfer is being processed by bank
    'TRANSFER_DONE',             // Transfer was completed
    'TRANSFER_FAILED',           // Transfer failed
    'TRANSFER_CANCELLED',        // Transfer was cancelled
];
```

### Account Events

```php
$accountEvents = [
    'ACCOUNT_STATUS_UPDATED',    // Account status was updated
];
```

### Complete Events Example

```php
$webhook = Asaas::webhooks()->create([
    'name' => 'Complete Event WebhookCreate',
    'url' => 'https://myapp.com/webhooks/all-events',
    'events' => [
        // Payment events
        'PAYMENT_CREATED',
        'PAYMENT_UPDATED',
        'PAYMENT_CONFIRMED',
        'PAYMENT_RECEIVED',
        
        // Subscription events
        'SUBSCRIPTION_CREATED',
        'SUBSCRIPTION_UPDATED',
        'SUBSCRIPTION_DELETED',
        
        // Transfer events
        'TRANSFER_CREATED',
        'TRANSFER_DONE',
        'TRANSFER_FAILED',
        
        // Account events
        'ACCOUNT_STATUS_UPDATED'
    ]
]);
```

## Send Types

Webhooks support two send types:

### SEQUENTIALLY
Events are sent one at a time, waiting for the previous one to complete before sending the next.

```php
$webhook = Asaas::webhooks()->create([
    'name' => 'Sequential WebhookCreate',
    'url' => 'https://myapp.com/webhook',
    'sendType' => 'SEQUENTIALLY',
    'events' => ['PAYMENT_CREATED', 'PAYMENT_CONFIRMED']
]);
```

### NON_SEQUENTIALLY
Events are sent simultaneously without waiting for previous requests to complete.

```php
$webhook = Asaas::webhooks()->create([
    'name' => 'Non-Sequential WebhookCreate',
    'url' => 'https://myapp.com/webhook',
    'sendType' => 'NON_SEQUENTIALLY',
    'events' => ['PAYMENT_CREATED', 'PAYMENT_CONFIRMED']
]);
```

## Webhook Security

### Authentication Token

You can set an authentication token that will be sent in the webhook request headers:

```php
$webhook = Asaas::webhooks()->create([
    'name' => 'Secure WebhookCreate',
    'url' => 'https://myapp.com/webhook',
    'authToken' => 'your-secret-token-here',
    'events' => ['PAYMENT_CONFIRMED']
]);
```

### Verifying Webhook Requests

When you receive a webhook request, verify it's from Asaas:

```php
// In your webhook endpoint
$headers = getallheaders();
$authToken = $headers['asaas-access-token'] ?? null;

if ($authToken !== 'your-secret-token-here') {
    http_response_code(401);
    exit('Unauthorized');
}

// Process the webhook data
$payload = json_decode(file_get_contents('php://input'), true);
$event = $payload['event'];
$data = $payload['payment'] ?? $payload['subscription'] ?? $payload['transfer'];

switch ($event) {
    case 'PAYMENT_CONFIRMED':
        // Handle payment confirmation
        handlePaymentConfirmed($data);
        break;
    case 'SUBSCRIPTION_CREATED':
        // Handle subscription creation
        handleSubscriptionCreated($data);
        break;
    // ... other events
}

// Always return 200 OK
http_response_code(200);
echo 'OK';
```

## Entity Reference

### Webhook Entity

Properties available when creating/updating webhooks:

```php
$webhook = new Webhook(
    name: 'WebhookCreate Name',           // string - WebhookCreate name
    url: 'https://example.com',     // string - WebhookCreate URL
    email: 'admin@example.com',     // string - Email for notifications
    enabled: true,                  // bool - Whether webhook is enabled
    interrupted: false,             // bool - Whether webhook is interrupted
    apiVersion: 3,                  // int - API version to use
    authToken: 'secret-token',      // string - Authentication token
    sendType: 'SEQUENTIALLY',       // string - Send type (SEQUENTIALLY, NON_SEQUENTIALLY)
    events: ['PAYMENT_CREATED']     // array - Events to listen for
);
```

### Webhook Response

Properties available in webhook responses:

```php
// Properties available in WebhookResponse
$webhook->id;                    // string - Unique identifier
$webhook->name;                  // string - WebhookCreate name
$webhook->url;                   // string - WebhookCreate URL
$webhook->email;                 // string - Email for notifications
$webhook->enabled;               // bool - Whether webhook is enabled
$webhook->interrupted;           // bool - Whether webhook is interrupted
$webhook->apiVersion;            // int - API version
$webhook->authToken;             // string - Authentication token
$webhook->sendType;              // string - Send type
$webhook->events;                // array - Events being listened for
```

## Best Practices

### 1. Handle Webhook Failures

```php
// In your webhook endpoint, implement proper error handling
try {
    $payload = json_decode(file_get_contents('php://input'), true);
    
    if (!$payload) {
        throw new Exception('Invalid JSON payload');
    }
    
    // Process the webhook
    processWebhook($payload);
    
    // Return success
    http_response_code(200);
    echo 'OK';
    
} catch (Exception $e) {
    // Log the error
    error_log("WebhookCreate error: " . $e->getMessage());
    
    // Return error status (Asaas will retry)
    http_response_code(500);
    echo 'Error: ' . $e->getMessage();
}
```

### 2. Implement Idempotency

```php
// Store processed webhook IDs to avoid duplicate processing
function processWebhook($payload) {
    $webhookId = $payload['id'] ?? null;
    
    if (!$webhookId) {
        throw new Exception('Missing webhook ID');
    }
    
    // Check if already processed
    if (isWebhookProcessed($webhookId)) {
        return; // Already processed, skip
    }
    
    // Process the webhook
    switch ($payload['event']) {
        case 'PAYMENT_CONFIRMED':
            handlePaymentConfirmed($payload['payment']);
            break;
        // ... other events
    }
    
    // Mark as processed
    markWebhookAsProcessed($webhookId);
}
```

### 3. Use Appropriate Events

Only subscribe to events you actually need:

```php
// Good - Only subscribe to necessary events
$webhook = Asaas::webhooks()->create([
    'name' => 'Payment WebhookCreate',
    'url' => 'https://myapp.com/webhook',
    'events' => [
        'PAYMENT_CONFIRMED',  // Only when payment is confirmed
        'PAYMENT_RECEIVED'    // Only when payment is received
    ]
]);

// Avoid - Subscribing to all events unnecessarily
$webhook = Asaas::webhooks()->create([
    'name' => 'All Events WebhookCreate',
    'url' => 'https://myapp.com/webhook',
    'events' => [
        'PAYMENT_CREATED',
        'PAYMENT_UPDATED',
        'PAYMENT_CONFIRMED',
        'PAYMENT_RECEIVED',
        // ... many more events you don't need
    ]
]);
```

### 4. Test Your Webhooks

```php
// Create a test webhook for development
$testWebhook = Asaas::webhooks()->create([
    'name' => 'Development Test WebhookCreate',
    'url' => 'https://webhook.site/your-unique-url', // Use webhook.site for testing
    'events' => ['PAYMENT_CREATED'],
    'enabled' => true
]);

// Create a test payment to trigger the webhook
$testPayment = Asaas::payments()->create([
    'customer' => 'cus_test_123',
    'billingType' => 'PIX',
    'value' => 1.00,
    'dueDate' => date('Y-m-d', strtotime('+1 day'))
]);
```

### 5. Monitor Webhook Health

```php
// Regularly check webhook status
$webhooks = Asaas::webhooks()->list();

foreach ($webhooks->data as $webhook) {
    if ($webhook->interrupted) {
        // WebhookCreate is having issues, investigate
        error_log("WebhookCreate {$webhook->name} is interrupted");
        
        // You might want to disable and re-enable it
        Asaas::webhooks()->update($webhook->id, ['enabled' => false]);
        sleep(1);
        Asaas::webhooks()->update($webhook->id, ['enabled' => true]);
    }
}
```

### 6. Use HTTPS URLs

Always use HTTPS URLs for webhooks to ensure security:

```php
// Good - HTTPS URL
$webhook = Asaas::webhooks()->create([
    'name' => 'Secure WebhookCreate',
    'url' => 'https://myapp.com/webhook', // HTTPS
    'events' => ['PAYMENT_CONFIRMED']
]);

// Bad - HTTP URL (insecure)
$webhook = Asaas::webhooks()->create([
    'name' => 'Insecure WebhookCreate',
    'url' => 'http://myapp.com/webhook', // HTTP - avoid this
    'events' => ['PAYMENT_CONFIRMED']
]);
```

## Common Error Scenarios

### Invalid URL

```php
try {
    $webhook = Asaas::webhooks()->create([
        'name' => 'Test WebhookCreate',
        'url' => 'invalid-url', // Invalid URL format
        'events' => ['PAYMENT_CREATED']
    ]);
} catch (BadRequestException $e) {
    echo "Error: " . $e->getMessage(); // URL validation error
}
```

### Invalid Events

```php
try {
    $webhook = Asaas::webhooks()->create([
        'name' => 'Test WebhookCreate',
        'url' => 'https://myapp.com/webhook',
        'events' => ['INVALID_EVENT'] // Invalid event name
    ]);
} catch (BadRequestException $e) {
    echo "Error: " . $e->getMessage(); // Invalid event error
}
```

## Related Documentation

- [Payment Resource](PAYMENTS.md) - For payment-related webhooks
- [Subscription Resource](SUBSCRIPTIONS.md) - For subscription-related webhooks
- [Transfer Resource](TRANSFERS.md) - For transfer-related webhooks
- [Error Handling](ERROR_HANDLING.md) - Error handling guide
