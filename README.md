# Asaas SDK for Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/leopaulo88/asaas-sdk-laravel.svg?style=flat-square)](https://packagist.org/packages/leopaulo88/asaas-sdk-laravel)
[![Total Downloads](https://img.shields.io/packagist/dt/leopaulo88/asaas-sdk-laravel.svg?style=flat-square)](https://packagist.org/packages/leopaulo88/asaas-sdk-laravel)
[![License](https://img.shields.io/packagist/l/leopaulo88/asaas-sdk-laravel.svg?style=flat-square)](https://packagist.org/packages/leopaulo88/asaas-sdk-laravel)

A comprehensive Laravel SDK for integrating with the Asaas payment platform. This package provides a clean, fluent interface for managing customers, payments, subscriptions, transfers, webhooks, and more.

## Features

- 🎯 **Complete API Coverage** - Support for all major Asaas API endpoints
- 🔄 **Webhook Management** - Full webhook configuration and event handling
- 💳 **Payment Processing** - PIX, Boleto, Credit Card payments with installments
- 📅 **Subscription Management** - Recurring payment subscriptions
- 💸 **Transfer Operations** - PIX and TED transfers between accounts
- 👥 **Customer Management** - Complete customer lifecycle management
- 🏦 **Account Operations** - Account information and sub-account creation
- 🔒 **Secure Tokenization** - PCI-compliant credit card tokenization
- 📊 **Financial Reports** - Balance and transaction statistics
- ⚡ **Fluent Interface** - Elegant, readable code with method chaining
- 🛡️ **Type Safety** - Comprehensive entity validation
- 🧪 **Well Tested** - Extensive test coverage with Pest PHP

## Installation

You can install the package via Composer:

```bash
composer require leopaulo88/asaas-sdk-laravel
```

Publish the configuration file:

```bash
php artisan vendor:publish --tag="asaas-config"
```

Add your Asaas credentials to your `.env` file:

```env
ASAAS_API_KEY=your_api_key_here
ASAAS_ENVIRONMENT=sandbox  # or 'production'
```

## Quick Start

### Basic Payment Creation

```php
use Leopaulo88\Asaas\Facades\Asaas;

// Create a customer
$customer = Asaas::customers()->create([
    'name' => 'John Doe',
    'email' => 'john@example.com',
    'cpfCnpj' => '12345678901'
]);

// Create a PIX payment
$payment = Asaas::payments()->create([
    'customer' => $customer->id,
    'billingType' => 'PIX',
    'value' => 100.00,
    'dueDate' => '2025-02-15',
    'description' => 'Product purchase'
]);

// Get PIX QR Code
$pixQrCode = Asaas::payments()->pixQrCode($payment->id);
echo "QR Code: {$pixQrCode->encodedImage}";
```

### Webhook Configuration

```php
// Create a webhook to receive payment notifications
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

### Subscription Management

```php
// Create a monthly subscription
$subscription = Asaas::subscriptions()->create([
    'customer' => $customer->id,
    'billingType' => 'CREDIT_CARD',
    'value' => 29.90,
    'nextDueDate' => '2025-02-01',
    'cycle' => 'MONTHLY',
    'description' => 'Premium Plan'
]);
```

## Available Resources

### Customer Management
```php
// Create customers
$customer = Asaas::customers()->create($data);

// List customers with filters
$customers = Asaas::customers()->list(['name' => 'John']);

// Update customer
$customer = Asaas::customers()->update($customerId, $data);

// Find specific customer
$customer = Asaas::customers()->find($customerId);
```

### Payment Processing
```php
// Create payments (PIX, Boleto, Credit Card)
$payment = Asaas::payments()->create($data);

// Get payment information
$payment = Asaas::payments()->find($paymentId);

// Process refunds
$refund = Asaas::payments()->refund($paymentId, $amount);

// Capture authorized payments
$payment = Asaas::payments()->capture($paymentId);
```

### Transfer Operations
```php
// PIX transfer
$transfer = Asaas::transfers()->create([
    'value' => 500.00,
    'pixAddressKey' => '11999999999',
    'pixAddressKeyType' => 'PHONE',
    'description' => 'PIX transfer'
]);

// Bank transfer (TED)
$transfer = Asaas::transfers()->create([
    'value' => 1000.00,
    'bankAccount' => [
        'bank' => ['code' => '033'],
        'accountName' => 'John Doe',
        'ownerName' => 'John Doe',
        'cpfCnpj' => '12345678901',
        'agency' => '1234',
        'account' => '56789-0'
    ],
    'operationType' => 'TED'
]);
```

### Webhook Management
```php
// Create webhook
$webhook = Asaas::webhooks()->create($data);

// List webhooks
$webhooks = Asaas::webhooks()->list();

// Update webhook
$webhook = Asaas::webhooks()->update($webhookId, $data);

// Remove webhook
Asaas::webhooks()->remove($webhookId);
```

### Account Operations
```php
// Get account information
$account = Asaas::accounts()->info();

// Create sub-account with webhooks
$account = Asaas::accounts()->create([
    'name' => 'Sub Account',
    'email' => 'sub@example.com',
    'cpfCnpj' => '12345678901',
    'webhooks' => [$webhookConfig]
]);
```

### Credit Card Tokenization
```php
// Tokenize credit card for secure storage
$token = Asaas::creditCards()->tokenize([
    'customer' => $customerId,
    'creditCard' => [
        'holderName' => 'John Doe',
        'number' => '4111111111111111',
        'expiryMonth' => '12',
        'expiryYear' => '2028',
        'ccv' => '123'
    ]
]);

// Use token in payments
$payment = Asaas::payments()->create([
    'customer' => $customerId,
    'billingType' => 'CREDIT_CARD',
    'value' => 150.00,
    'creditCardToken' => $token->creditCardToken
]);
```

### Financial Information
```php
// Get account balance
$balance = Asaas::finance()->balance();

// Get payment statistics
$stats = Asaas::finance()->statistics();

// Get split statistics
$splitStats = Asaas::finance()->splitStatistics();
```

## Entity-Based Approach

The SDK supports both array-based and entity-based approaches for type safety and better IDE support:

```php
use Leopaulo88\Asaas\Entities\Payment\PaymentCreate;use Leopaulo88\Asaas\Entities\Webhook\WebhookCreate;

// Using entities with fluent interface
$payment = PaymentCreate::make()
    ->customer('cus_123456')
    ->billingType('PIX')
    ->value(100.00)
    ->dueDate('2025-02-15')
    ->description('Product purchase');

$result = Asaas::payments()->create($payment);

// WebhookCreate entity
$webhook = (new WebhookCreate)
    ->name('Payment WebhookCreate')
    ->url('https://myapp.com/webhook')
    ->enabled(true)
    ->sendType('SEQUENTIALLY')
    ->events(['PAYMENT_CONFIRMED', 'PAYMENT_RECEIVED']);
```

## Environment Configuration

The package supports both sandbox and production environments:

```php
// Use specific environment
$payment = Asaas::withApiKey($apiKey, 'production')
    ->payments()
    ->create($data);

// Multiple tenants/accounts
$payment = Asaas::withApiKey($tenant->api_key)
    ->payments()
    ->create($data);
```

## Event Handling

### Available Webhook Events

#### Payment Events
- `PAYMENT_CREATED` - Payment was created
- `PAYMENT_CONFIRMED` - Payment was confirmed
- `PAYMENT_RECEIVED` - Payment was received
- `PAYMENT_OVERDUE` - Payment is overdue
- `PAYMENT_REFUNDED` - Payment was refunded

#### Subscription Events
- `SUBSCRIPTION_CREATED` - Subscription was created
- `SUBSCRIPTION_UPDATED` - Subscription was updated
- `SUBSCRIPTION_DELETED` - Subscription was deleted

#### Transfer Events
- `TRANSFER_CREATED` - Transfer was created
- `TRANSFER_DONE` - Transfer was completed
- `TRANSFER_FAILED` - Transfer failed

### Webhook Implementation Example

```php
// In your webhook controller
public function handle(Request $request)
{
    $payload = $request->json()->all();
    $event = $payload['event'];
    
    switch ($event) {
        case 'PAYMENT_CONFIRMED':
            $this->handlePaymentConfirmed($payload['payment']);
            break;
        case 'SUBSCRIPTION_CREATED':
            $this->handleSubscriptionCreated($payload['subscription']);
            break;
    }
    
    return response('OK', 200);
}
```

## Error Handling

The SDK provides comprehensive error handling:

```php
use Leopaulo88\Asaas\Exceptions\{
    BadRequestException,
    UnauthorizedException,
    NotFoundException
};

try {
    $payment = Asaas::payments()->create($data);
} catch (BadRequestException $e) {
    // Validation errors
    $errors = $e->getErrors();
    foreach ($errors as $field => $messages) {
        echo "Field {$field}: " . implode(', ', $messages);
    }
} catch (UnauthorizedException $e) {
    // Invalid API key
    echo "Authentication failed: " . $e->getMessage();
} catch (NotFoundException $e) {
    // Resource not found
    echo "Resource not found: " . $e->getMessage();
}
```

## Testing

Run the test suite:

```bash
./vendor/bin/pest
```

## Documentation

Comprehensive documentation is available in the `docs/` directory:

- [Customer Management](docs/CUSTOMERS.md)
- [Payment Processing](docs/PAYMENTS.md)
- [Subscription Management](docs/SUBSCRIPTIONS.md)
- [Transfer Operations](docs/TRANSFERS.md)
- [Webhook Configuration](docs/WEBHOOKS.md)
- [Account Management](docs/ACCOUNTS.md)
- [Credit Card Tokenization](docs/CREDIT_CARDS.md)
- [Entity Reference](docs/ENTITIES.md)
- [Error Handling](docs/ERROR_HANDLING.md)

## Contributing

Please see [CONTRIBUTING](docs/CONTRIBUTING.md) for details on how to contribute to this project.

## Security

If you discover any security-related issues, please email the maintainer instead of using the issue tracker.

## License

This package is open-sourced software licensed under the [MIT license](LICENSE.md).

## Support

- 📖 [Official Asaas API Documentation](https://docs.asaas.com/)
- 🐛 [Report Issues](https://github.com/leopaulo88/asaas-sdk-laravel/issues)
- 💬 [Discussions](https://github.com/leopaulo88/asaas-sdk-laravel/discussions)

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

---

Made with ❤️ for the Laravel community
