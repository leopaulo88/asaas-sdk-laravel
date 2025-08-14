# Asaas SDK for Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/leopaulo88/asaas-sdk-laravel.svg?style=flat-square)](https://packagist.org/packages/leopaulo88/asaas-sdk-laravel)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/leopaulo88/asaas-sdk-laravel/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/leopaulo88/asaas-sdk-laravel/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/leopaulo88/asaas-sdk-laravel/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/leopaulo88/asaas-sdk-laravel/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/leopaulo88/asaas-sdk-laravel.svg?style=flat-square)](https://packagist.org/packages/leopaulo88/asaas-sdk-laravel)

A modern, type-safe Laravel SDK for the Asaas payment gateway. This package provides an elegant way to interact with the Asaas API using typed entities, automatic data hydration, and Laravel-style fluent interfaces.

## Features

- ✨ **Type-safe entities** with full IDE support
- 🔄 **Automatic data hydration** between arrays and objects
- 🎯 **Fluent interface** for building requests
- 📦 **Laravel integration** with service providers and facades
- 🧪 **Comprehensive test coverage**
- 📚 **Detailed documentation**
- 🛡️ **Built-in error handling** and validation

## Installation

Install the package via Composer:

```bash
composer require leopaulo88/asaas-sdk-laravel
```

### Quick Installation

Use the built-in installation command to set up the package automatically:

```bash
php artisan asaas:install
```

This command will:
- Publish the configuration file to `config/asaas.php`
- Ask if you want to star the repository on GitHub

### Manual Installation

Alternatively, you can publish the configuration file manually:

```bash
php artisan vendor:publish --tag="asaas-sdk-laravel-config"
```

This will create a `config/asaas.php` file:

```php
<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Asaas API Configuration
    |--------------------------------------------------------------------------
    */

    'api_key' => env('ASAAS_API_KEY'),
    
    'environment' => env('ASAAS_ENVIRONMENT', 'sandbox'), // 'sandbox' or 'production'
    
    'timeout' => env('ASAAS_TIMEOUT', 30),
    
    'retry_attempts' => env('ASAAS_RETRY_ATTEMPTS', 3),
];
```

### Environment Variables

Add the following variables to your `.env` file:

```env
ASAAS_API_KEY=your_api_key_here
ASAAS_ENVIRONMENT=sandbox
```

### Using Different API Keys

You can use different API keys for specific operations using the `withApiKey()` method:

```php
use Leopaulo88\Asaas\Facades\Asaas;

// Use default API key from config
$customer = Asaas::customers()->create($data);

// Use a different API key for specific operation
$payment = Asaas::withApiKey('different_api_key_here')
    ->payments()
    ->create($paymentData);

// Use different API key with different environment
$subscription = Asaas::withApiKey('production_api_key', 'production')
    ->subscriptions()
    ->create($subscriptionData);
```

### Multi-Tenant Usage

For multi-tenant applications where each tenant has their own Asaas account:

```php
class TenantPaymentService
{
    public function createPaymentForTenant(int $tenantId, array $paymentData)
    {
        $tenant = Tenant::find($tenantId);
        
        return Asaas::withApiKey($tenant->asaas_api_key, $tenant->asaas_environment)
            ->payments()
            ->create($paymentData);
    }
    
    public function listTenantCustomers(int $tenantId)
    {
        $tenant = Tenant::find($tenantId);
        
        return Asaas::withApiKey($tenant->asaas_api_key)
            ->customers()
            ->list();
    }
}
```

## Quick Start

### Using the Facade

```php
use Leopaulo88\Asaas\Facades\Asaas;

// Create a customer
$customer = Asaas::customers()->create([
    'name' => 'John Doe',
    'email' => 'john@example.com',
    'cpfCnpj' => '12345678901'
]);

// Create a payment
$payment = Asaas::payments()->create([
    'customer' => $customer->id,
    'billingType' => 'BOLETO',
    'value' => 100.50,
    'dueDate' => '2025-12-31'
]);
```

### Using Dependency Injection

```php
use Leopaulo88\Asaas\Asaas;

class PaymentService 
{
    public function __construct(
        private Asaas $asaas
    ) {}
    
    public function createPayment(array $data)
    {
        return $this->asaas->payments()->create($data);
    }
}
```

## Available Resources

| Resource | Description | Documentation |
|----------|-------------|---------------|
| **Customers** | Manage customers | [Customer Resource](docs/CUSTOMERS.md) |
| **Payments** | Handle payments and charges | [Payment Resource](docs/PAYMENTS.md) |
| **Subscriptions** | Manage recurring subscriptions | [Subscription Resource](docs/SUBSCRIPTIONS.md) |
| **Installments** | Create and manage installment payments | [Installment Resource](docs/INSTALLMENTS.md) |
| **Transfers** | Create and manage transfers between accounts | [Transfer Resource](docs/TRANSFERS.md) |
| **Credit Cards** | Tokenize credit cards | [Credit Card Resource](docs/CREDIT_CARDS.md) |
| **Accounts** | Account management | [Account Resource](docs/ACCOUNTS.md) |
| **Finance** | Retrieve financial information | [Finance Resource](docs/FINANCE.md) |

## Entity Usage Patterns

All entities in this SDK support multiple instantiation patterns:

### 1. Using `new` Constructor

```php
use Leopaulo88\Asaas\Entities\Customer\CustomerCreate;

$customer = new CustomerCreate(
    name: 'John Doe',
    email: 'john@example.com',
    cpfCnpj: '12345678901'
);
```

### 2. Using `make()` Static Method
```php
$customer = CustomerCreate::make()
    ->name('John Doe')
    ->email('john@example.com')
    ->cpfCnpj('12345678901');
```

### 3. Using `fromArray()` Static Method
```php
$customer = CustomerCreate::fromArray([
    'name' => 'John Doe',
    'email' => 'john@example.com',
    'cpfCnpj' => '12345678901'
]);
```

## Complete Entity Reference

### Customer Entities
- **CustomerCreate** - For creating customers
- **CustomerUpdate** - For updating customers  
- **CustomerResponse** - API response entity

### Payment Entities
- **PaymentCreate** - For creating payments
- **PaymentUpdate** - For updating payments
- **PaymentResponse** - API response entity
- **PaymentCreditCard** - For credit card payments
- **BillingInfoResponse** - Billing information response

### Subscription Entities
- **SubscriptionCreate** - For creating subscriptions
- **SubscriptionUpdate** - For updating subscriptions
- **SubscriptionResponse** - API response entity
- **SubscriptionUpdateCreditCard** - For updating subscription credit cards

### Installment Entities
- **InstallmentCreate** - For creating installments
- **InstallmentResponse** - API response entity

### Transfer Entities
- **TransferCreate** - For creating transfers
- **TransferResponse** - API response entity

### Credit Card Entities
- **CreditCardTokenCreate** - For tokenizing credit cards
- **CreditCardTokenResponse** - Token response entity

### Common Entities
- **CreditCard** - Credit card information
- **CreditCardHolderInfo** - Cardholder information
- **Discount** - Discount configuration
- **Fine** - Fine configuration
- **Interest** - Interest configuration
- **Split** - Payment splitting
- **Deleted** - Deletion confirmation
- **Refund** - Refund information

### List and Response Entities
- **ListResponse** - Paginated list responses
- **AccountResponse** - Account information response

## Finance Resource

The Finance Resource allows you to retrieve financial information from your Asaas account, such as balance, payment statistics, and split statistics.

### Example Usage

```php
use Leopaulo88\Asaas\Facades\Asaas;

// Get account balance
$balance = Asaas::finance()->balance();
echo "Balance: {$balance->balance}";

// Get payment statistics
$stats = Asaas::finance()->statistics(['foo' => 'bar']);
echo "Quantity: {$stats->quantity}";
echo "Total value: {$stats->value}";
echo "Net value: {$stats->netValue}";

// Get split statistics
$splitStats = Asaas::finance()->splitStatistics();
echo "Income: {$splitStats->income}";
echo "Outcome: {$splitStats->outcome}";
```

For more details, see [docs/FINANCE.md](docs/FINANCE.md).

## Error Handling

The SDK provides specific exceptions for different error scenarios:

```php
use Leopaulo88\Asaas\Exceptions\{
    AsaasException,
    BadRequestException,
    UnauthorizedException,
    NotFoundException
};

try {
    $payment = Asaas::payments()->create($data);
} catch (BadRequestException $e) {
    // Handle validation errors
    $errors = $e->getErrors();
} catch (UnauthorizedException $e) {
    // Handle authentication errors
} catch (NotFoundException $e) {
    // Handle not found errors
} catch (AsaasException $e) {
    // Handle other API errors
}
```

## Testing

The package includes comprehensive test coverage. Run tests with:

```bash
composer test
```

## Documentation

- [Customer Management](docs/CUSTOMERS.md)
- [Payment Processing](docs/PAYMENTS.md)
- [Subscription Management](docs/SUBSCRIPTIONS.md)
- [Installment Management](docs/INSTALLMENTS.md)
- [Transfer Management](docs/TRANSFERS.md)
- [Credit Card Tokenization](docs/CREDIT_CARDS.md)
- [Account Information](docs/ACCOUNTS.md)
- [Entity Reference](docs/ENTITIES.md)
- [Error Handling](docs/ERROR_HANDLING.md)
- [Finance Resource](docs/FINANCE.md)

## Contributing

Please see [CONTRIBUTING](docs/CONTRIBUTING.md) for details on how to contribute.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Leanderson Paulo](https://github.com/leopaulo88)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
