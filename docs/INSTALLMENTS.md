# Installment Resource

The `InstallmentResource` provides methods for creating and managing installment payments through the Asaas API. Installments allow you to split payments into multiple charges, making it easier for customers to pay larger amounts over time.

## Quick Start

```php
use Leopaulo88\Asaas\Facades\Asaas;

// Create an installment
$installment = Asaas::installments()->create([
    'customer' => 'cus_000005492944',
    'billingType' => 'CREDIT_CARD',
    'value' => 500.00,
    'installmentCount' => 5,
    'installmentValue' => 100.00,
    'description' => 'Compra parcelada em 5x'
]);
```

## Available Methods

### Create Installment

Create a new installment payment.

```php
use Leopaulo88\Asaas\Entities\Installment\InstallmentCreate;

// Using array
$installment = Asaas::installments()->create([
    'customer' => 'cus_000005492944',
    'billingType' => 'CREDIT_CARD',
    'value' => 500.00,
    'installmentCount' => 5,
    'installmentValue' => 100.00,
    'description' => 'Produto X parcelado',
    'dueDate' => '2024-01-15',
    'creditCard' => [
        'holderName' => 'João Silva',
        'number' => '4111111111111111',
        'expiryMonth' => '12',
        'expiryYear' => '2028',
        'ccv' => '123'
    ],
    'creditCardHolderInfo' => [
        'name' => 'João Silva',
        'email' => 'joao@example.com',
        'cpfCnpj' => '12345678901',
        'postalCode' => '01310-100',
        'addressNumber' => '123',
        'phone' => '11987654321'
    ]
]);

// Using entity
$installmentData = InstallmentCreate::make()
    ->customer('cus_000005492944')
    ->billingType('CREDIT_CARD')
    ->value(500.00)
    ->installmentCount(5)
    ->installmentValue(100.00)
    ->description('Produto X parcelado');

$installment = Asaas::installments()->create($installmentData);
```

### List Installments

Retrieve a paginated list of installments with optional filters.

```php
// List all installments
$installments = Asaas::installments()->list();

// List with filters
$installments = Asaas::installments()->list([
    'customer' => 'cus_000005492944',
    'status' => 'PENDING',
    'offset' => 0,
    'limit' => 20,
    'dateCreated[ge]' => '2024-01-01',
    'dateCreated[le]' => '2024-12-31'
]);

// Access results
foreach ($installments->data as $installment) {
    echo "Installment ID: {$installment->id}\n";
    echo "Value: R$ {$installment->value}\n";
    echo "Status: {$installment->status}\n";
}

echo "Total: {$installments->totalCount}\n";
```

### Find Installment

Retrieve a specific installment by its ID.

```php
$installment = Asaas::installments()->find('ins_000000123456');

echo "Customer: {$installment->customer}\n";
echo "Total Value: R$ {$installment->value}\n";
echo "Installments: {$installment->installmentCount}\n";
echo "Status: {$installment->status}\n";
```

### Remove Installment

Delete an installment (only possible if no payments have been processed).

```php
$deleted = Asaas::installments()->remove('ins_000000123456');

if ($deleted->deleted) {
    echo "Installment successfully deleted\n";
}
```

### List Payments of Installment

Retrieve all payments associated with a specific installment.

```php
// List all payments
$payments = Asaas::installments()->listPayments('ins_000000123456');

// List with filters
$payments = Asaas::installments()->listPayments('ins_000000123456', [
    'status' => 'CONFIRMED',
    'offset' => 0,
    'limit' => 10
]);

foreach ($payments->data as $payment) {
    echo "Payment ID: {$payment->id}\n";
    echo "Due Date: {$payment->dueDate}\n";
    echo "Value: R$ {$payment->value}\n";
    echo "Status: {$payment->status}\n";
}
```

### Refund Installment

Process a refund for an installment.

```php
$refundedInstallment = Asaas::installments()->refund('ins_000000123456');

echo "Refund Status: {$refundedInstallment->status}\n";
```

### Update Payment Splits

Update the payment splitting configuration for an installment.

```php
use Leopaulo88\Asaas\Entities\Common\Split;

$splits = [
    Split::make()
        ->walletId('wal_000001234567')
        ->fixedValue(50.00),
    Split::make()
        ->walletId('wal_000007654321')
        ->percentualValue(30.0)
];

$updatedSplits = Asaas::installments()->updateSplits('ins_000000123456', $splits);

foreach ($updatedSplits as $split) {
    echo "Wallet: {$split->walletId}\n";
    echo "Status: {$split->status}\n";
}
```

## Installment Entities

### InstallmentCreate

Entity for creating new installments:

```php
use Leopaulo88\Asaas\Entities\Installment\InstallmentCreate;
use Leopaulo88\Asaas\Entities\Common\CreditCard;
use Leopaulo88\Asaas\Entities\Common\CreditCardHolderInfo;

$installment = InstallmentCreate::make()
    ->customer('cus_000005492944')
    ->billingType('CREDIT_CARD')
    ->value(1000.00)
    ->installmentCount(10)
    ->installmentValue(100.00)
    ->description('Compra parcelada')
    ->dueDate('2024-01-15')
    ->creditCard(
        CreditCard::make()
            ->holderName('João Silva')
            ->number('4111111111111111')
            ->expiryMonth('12')
            ->expiryYear('2028')
            ->ccv('123')
    )
    ->creditCardHolderInfo(
        CreditCardHolderInfo::make()
            ->name('João Silva')
            ->email('joao@example.com')
            ->cpfCnpj('12345678901')
            ->postalCode('01310-100')
            ->addressNumber('123')
            ->phone('11987654321')
    );
```

### InstallmentResponse

Response entity containing installment information:

```php
// Properties available in InstallmentResponse
$installment->id;                    // string - Unique identifier
$installment->customer;              // string - Customer ID
$installment->billingType;           // string - Payment method
$installment->value;                 // float - Total value
$installment->installmentCount;      // int - Number of installments
$installment->installmentValue;      // float - Value per installment
$installment->description;           // string - Description
$installment->status;                // string - Current status
$installment->dateCreated;           // string - Creation date
$installment->dueDate;              // string - First installment due date
$installment->originalDueDate;       // string - Original due date
```

## Billing Types

Supported billing types for installments:

- `CREDIT_CARD` - Credit card payments
- `BOLETO` - Bank slip payments
- `PIX` - PIX payments

## Installment Status

Possible installment statuses:

- `PENDING` - Awaiting payment
- `RECEIVED` - Payment received
- `CONFIRMED` - Payment confirmed
- `OVERDUE` - Payment overdue
- `REFUNDED` - Payment refunded
- `RECEIVED_IN_CASH` - Received in cash
- `REFUND_REQUESTED` - Refund requested
- `REFUND_IN_PROGRESS` - Refund in progress
- `CHARGEBACK_REQUESTED` - Chargeback requested
- `CHARGEBACK_DISPUTE` - Chargeback in dispute
- `AWAITING_CHARGEBACK_REVERSAL` - Awaiting chargeback reversal

## Query Parameters

### List Installments Parameters

- `customer` (string) - Filter by customer ID
- `status` (string) - Filter by installment status
- `offset` (int) - Starting element for pagination (default: 0)
- `limit` (int) - Number of elements to return, max 100 (default: 10)
- `dateCreated[ge]` (string) - Filter by creation date (greater than or equal) - Format: YYYY-MM-DD
- `dateCreated[le]` (string) - Filter by creation date (less than or equal) - Format: YYYY-MM-DD

### List Payments Parameters

- `status` (string) - Filter payments by status
- `offset` (int) - Starting element for pagination (default: 0)
- `limit` (int) - Number of elements to return, max 100 (default: 10)

## Error Handling

```php
use Leopaulo88\Asaas\Exceptions\{
    BadRequestException,
    NotFoundException,
    UnauthorizedException
};

try {
    $installment = Asaas::installments()->create($data);
} catch (BadRequestException $e) {
    // Handle validation errors
    foreach ($e->getErrors() as $error) {
        echo "Error: {$error['description']}\n";
    }
} catch (NotFoundException $e) {
    echo "Installment not found\n";
} catch (UnauthorizedException $e) {
    echo "Invalid API key or permissions\n";
}
```

## Best Practices

### 1. Validate Data Before Creating

```php
// Always validate required fields
$requiredFields = ['customer', 'billingType', 'value', 'installmentCount'];
foreach ($requiredFields as $field) {
    if (empty($data[$field])) {
        throw new InvalidArgumentException("Field {$field} is required");
    }
}
```

### 2. Handle Credit Card Data Securely

```php
// Never log or store credit card information
$installment = Asaas::installments()->create([
    'customer' => $customerId,
    'billingType' => 'CREDIT_CARD',
    'value' => $totalValue,
    'installmentCount' => $installments,
    'creditCard' => $creditCardData, // Make sure this comes from a secure source
    'creditCardHolderInfo' => $holderInfo
]);

// Clear sensitive data after use
unset($creditCardData, $holderInfo);
```

### 3. Monitor Installment Status

```php
// Regularly check installment status
$installment = Asaas::installments()->find($installmentId);

switch ($installment->status) {
    case 'PENDING':
        // Send payment reminder
        break;
    case 'OVERDUE':
        // Handle overdue payment
        break;
    case 'RECEIVED':
        // Process successful payment
        break;
}
```

### 4. Use Pagination for Large Lists

```php
$offset = 0;
$limit = 100;

do {
    $installments = Asaas::installments()->list([
        'offset' => $offset,
        'limit' => $limit
    ]);
    
    foreach ($installments->data as $installment) {
        // Process installment
    }
    
    $offset += $limit;
} while ($installments->hasMore);
```

## Related Documentation

- [Payment Resource](PAYMENTS.md) - For individual payment management
- [Customer Resource](CUSTOMERS.md) - For customer management
- [Entity Reference](ENTITIES.md) - Complete entity documentation
- [Error Handling](ERROR_HANDLING.md) - Error handling guide
