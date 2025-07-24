# Transfer Resource

The `TransferResource` provides methods for creating and managing transfers through the Asaas API. Transfers allow you to move funds between accounts, supporting both bank transfers (TED) and PIX transactions.

## Quick Start

```php
use Leopaulo88\Asaas\Facades\Asaas;

// Create a PIX transfer
$transfer = Asaas::transfers()->create([
    'value' => 500.00,
    'pixAddressKey' => '11999999999',
    'pixAddressKeyType' => 'PHONE',
    'description' => 'PIX transfer to mobile number'
]);
```

## Available Methods

### Create Transfer

Create a new transfer.

```php
use Leopaulo88\Asaas\Entities\Transfer\TransferCreate;
use Leopaulo88\Asaas\Enums\TransferOperationType;
use Leopaulo88\Asaas\Enums\PixAddressKeyType;

// PIX Transfer using array
$pixTransfer = Asaas::transfers()->create([
    'value' => 250.00,
    'pixAddressKey' => 'john@example.com',
    'pixAddressKeyType' => 'EMAIL',
    'description' => 'PIX transfer payment',
    'scheduleDate' => '2024-02-15'
]);

// Bank Transfer (TED) using array
$bankTransfer = Asaas::transfers()->create([
    'value' => 1000.00,
    'bankAccount' => [
        'bank' => [
            'code' => '033', // Santander
        ],
        'accountName' => 'John Doe',
        'ownerName' => 'John Doe',
        'cpfCnpj' => '12345678901',
        'agency' => '1234',
        'account' => '56789-0',
        'accountDigit' => '0',
    ],
    'operationType' => 'TED',
    'description' => 'Bank transfer to external account'
]);

// Using entity
$transferData = TransferCreate::make()
    ->value(500.00)
    ->pixAddressKey('11987654321')
    ->pixAddressKeyType(PixAddressKeyType::PHONE)
    ->description('Transfer using entity')
    ->scheduleDate(Carbon::parse('2024-02-20'));

$transfer = Asaas::transfers()->create($transferData);
```

### List Transfers

Retrieve a paginated list of transfers with optional filters.

```php
// List all transfers
$transfers = Asaas::transfers()->list();

// List with filters
$transfers = Asaas::transfers()->list([
    'type' => 'PIX',
    'status' => 'DONE',
    'offset' => 0,
    'limit' => 20,
    'dateCreated[ge]' => '2024-01-01',
    'dateCreated[le]' => '2024-12-31'
]);

// Access results
foreach ($transfers->data as $transfer) {
    echo "Transfer ID: {$transfer->id}\n";
    echo "Value: $ {$transfer->value}\n";
    echo "Type: {$transfer->type->value}\n";
    echo "Status: {$transfer->status->value}\n";
}

echo "Total: {$transfers->totalCount}\n";
```

### Find Transfer

Retrieve a specific transfer by its ID.

```php
$transfer = Asaas::transfers()->find('tra_000000123456');

echo "Transfer ID: {$transfer->id}\n";
echo "Value: $ {$transfer->value}\n";
echo "Type: {$transfer->type->value}\n";
echo "Status: {$transfer->status->value}\n";
echo "Operation Type: {$transfer->operationType->value}\n";

// Access schedule date
if ($transfer->scheduleDate) {
    echo "Scheduled for: {$transfer->scheduleDate->format('Y-m-d')}\n";
}

// Access bank account details (for TED transfers)
if ($transfer->bankAccount) {
    echo "Bank: {$transfer->bankAccount->bank->name}\n";
    echo "Account: {$transfer->bankAccount->account}\n";
}
```

### Cancel Transfer

Cancel a transfer (only possible for pending transfers).

```php
$cancelledTransfer = Asaas::transfers()->cancel('tra_000000123456');

if ($cancelledTransfer->status === TransferStatus::CANCELLED) {
    echo "Transfer successfully cancelled\n";
}
```

## Transfer Entities

### TransferCreate

Entity for creating new transfers:

```php
use Leopaulo88\Asaas\Entities\Transfer\TransferCreate;
use Leopaulo88\Asaas\Entities\Common\BankAccount;
use Leopaulo88\Asaas\Enums\TransferOperationType;
use Leopaulo88\Asaas\Enums\PixAddressKeyType;
use Carbon\Carbon;

// PIX Transfer
$pixTransfer = TransferCreate::make()
    ->value(300.00)
    ->pixAddressKey('john@example.com')
    ->pixAddressKeyType(PixAddressKeyType::EMAIL)
    ->description('PIX payment')
    ->scheduleDate(Carbon::tomorrow());

// Bank Transfer with BankAccount entity
$bankAccount = BankAccount::make()
    ->bank(['code' => '341']) // Itaú
    ->accountName('Jane Smith')
    ->ownerName('Jane Smith')
    ->cpfCnpj('98765432100')
    ->agency('5678')
    ->account('12345-6')
    ->accountDigit('7');

$bankTransfer = TransferCreate::make()
    ->value(750.00)
    ->bankAccount($bankAccount)
    ->operationType(TransferOperationType::TED)
    ->description('TED transfer')
    ->externalReference('ORDER-12345');
```

### TransferResponse

Response entity containing transfer information:

```php
// Properties available in TransferResponse
$transfer->id;                    // string - Unique identifier
$transfer->type;                  // TransferType enum (PIX, TED, INTERNAL)
$transfer->value;                 // float - Transfer amount
$transfer->netValue;              // float - Net amount after fees
$transfer->status;                // TransferStatus enum
$transfer->transferFee;           // float - Transfer fee charged
$transfer->dateCreated;           // Carbon - Creation date
$transfer->scheduleDate;          // Carbon - Scheduled execution date
$transfer->effectiveDate;         // Carbon - When transfer was executed
$transfer->operationType;         // TransferOperationType enum
$transfer->description;           // string - Transfer description
$transfer->externalReference;     // string - External reference ID
$transfer->failReason;           // string - Failure reason if applicable
$transfer->pixAddressKey;        // string - PIX key used
$transfer->bankAccount;          // BankAccount - Bank details for TED
$transfer->authorized;           // bool - Authorization status
$transfer->endToEndIdentifier;   // string - E2E ID for PIX
$transfer->transactionReceiptUrl; // string - Receipt URL
```

## Transfer Types

### PIX Transfers

PIX is Brazil's instant payment system supporting various key types:

```php
use Leopaulo88\Asaas\Enums\PixAddressKeyType;

// Phone number
$transfer = Asaas::transfers()->create([
    'value' => 100.00,
    'pixAddressKey' => '11999999999',
    'pixAddressKeyType' => 'PHONE'
]);

// Email
$transfer = Asaas::transfers()->create([
    'value' => 200.00,
    'pixAddressKey' => 'user@example.com',
    'pixAddressKeyType' => 'EMAIL'
]);

// CPF/CNPJ
$transfer = Asaas::transfers()->create([
    'value' => 300.00,
    'pixAddressKey' => '12345678901',
    'pixAddressKeyType' => 'CPF'
]);

// Random key
$transfer = Asaas::transfers()->create([
    'value' => 400.00,
    'pixAddressKey' => '123e4567-e89b-12d3-a456-426614174000',
    'pixAddressKeyType' => 'EVP'
]);
```

### Bank Transfers (TED)

Traditional bank transfers using account details:

```php
$transfer = Asaas::transfers()->create([
    'value' => 1500.00,
    'bankAccount' => [
        'bank' => [
            'code' => '001', // Banco do Brasil
        ],
        'accountName' => 'Company ABC',
        'ownerName' => 'Company ABC LTDA',
        'cpfCnpj' => '12345678000195', // CNPJ
        'agency' => '1234',
        'account' => '56789-0',
        'accountDigit' => '1',
    ],
    'operationType' => 'TED',
    'description' => 'Payment to supplier'
]);
```

## Transfer Status

Possible transfer statuses:

- `PENDING` - Awaiting processing
- `BANK_PROCESSING` - Being processed by bank
- `DONE` - Successfully completed
- `CANCELLED` - Cancelled by user
- `FAILED` - Transfer failed

## Query Parameters

### List Transfers Parameters

- `type` (string) - Filter by transfer type (`PIX`, `TED`, `INTERNAL`)
- `status` (string) - Filter by status
- `offset` (int) - Starting element for pagination (default: 0)
- `limit` (int) - Number of elements to return, max 100 (default: 10)
- `dateCreated[ge]` (string) - Filter by creation date (greater than or equal) - Format: YYYY-MM-DD
- `dateCreated[le]` (string) - Filter by creation date (less than or equal) - Format: YYYY-MM-DD

## Scheduled Transfers

You can schedule transfers for future execution:

```php
use Carbon\Carbon;

// Schedule for tomorrow
$transfer = Asaas::transfers()->create([
    'value' => 500.00,
    'pixAddressKey' => '11999999999',
    'pixAddressKeyType' => 'PHONE',
    'scheduleDate' => Carbon::tomorrow()->format('Y-m-d'),
    'description' => 'Scheduled PIX transfer'
]);

// Schedule for specific date
$transfer = Asaas::transfers()->create([
    'value' => 1000.00,
    'bankAccount' => [
        'bank' => ['code' => '237'], // Bradesco
        'accountName' => 'John Doe',
        'ownerName' => 'John Doe',
        'cpfCnpj' => '12345678901',
        'agency' => '1234',
        'account' => '56789-0',
    ],
    'scheduleDate' => '2024-03-15',
    'description' => 'Scheduled TED transfer'
]);
```

## Error Handling

```php
use Leopaulo88\Asaas\Exceptions\{
    BadRequestException,
    NotFoundException,
    UnauthorizedException
};

try {
    $transfer = Asaas::transfers()->create($data);
} catch (BadRequestException $e) {
    // Handle validation errors
    foreach ($e->getErrors() as $error) {
        echo "Error: {$error['description']}\n";
    }
} catch (NotFoundException $e) {
    echo "Transfer not found\n";
} catch (UnauthorizedException $e) {
    echo "Invalid API key or insufficient permissions\n";
}
```

## Common Error Scenarios

### Invalid PIX Key
```php
// This will throw BadRequestException
$transfer = Asaas::transfers()->create([
    'value' => 100.00,
    'pixAddressKey' => 'invalid-email', // Invalid email format
    'pixAddressKeyType' => 'EMAIL'
]);
```

### Insufficient Balance
```php
// This will throw BadRequestException if account has insufficient funds
$transfer = Asaas::transfers()->create([
    'value' => 10000.00, // Amount exceeds available balance
    'pixAddressKey' => 'valid@email.com',
    'pixAddressKeyType' => 'EMAIL'
]);
```

### Invalid Bank Code
```php
// This will throw BadRequestException
$transfer = Asaas::transfers()->create([
    'value' => 500.00,
    'bankAccount' => [
        'bank' => ['code' => '999'], // Invalid bank code
        'accountName' => 'John Doe',
        // ... other fields
    ]
]);
```

## Best Practices

### 1. Validate Input Data

```php
// Always validate required fields
$requiredFields = ['value'];
foreach ($requiredFields as $field) {
    if (empty($data[$field])) {
        throw new InvalidArgumentException("Field {$field} is required");
    }
}

// Validate transfer type-specific fields
if ($data['type'] === 'PIX') {
    if (empty($data['pixAddressKey']) || empty($data['pixAddressKeyType'])) {
        throw new InvalidArgumentException("PIX transfers require pixAddressKey and pixAddressKeyType");
    }
}
```

### 2. Handle Scheduled Transfers

```php
// Check if transfer is scheduled
$transfer = Asaas::transfers()->find($transferId);

if ($transfer->scheduleDate && $transfer->scheduleDate->isFuture()) {
    echo "Transfer is scheduled for: {$transfer->scheduleDate->format('Y-m-d H:i:s')}\n";
    
    // You can cancel scheduled transfers
    if ($transfer->status === TransferStatus::PENDING) {
        $cancelled = Asaas::transfers()->cancel($transferId);
    }
}
```

### 3. Monitor Transfer Status

```php
// Regularly check transfer status
$transfer = Asaas::transfers()->find($transferId);

switch ($transfer->status) {
    case TransferStatus::PENDING:
        // Transfer is waiting to be processed
        break;
    case TransferStatus::BANK_PROCESSING:
        // Transfer is being processed by the bank
        break;
    case TransferStatus::DONE:
        // Transfer completed successfully
        break;
    case TransferStatus::FAILED:
        // Transfer failed - check failReason
        echo "Transfer failed: {$transfer->failReason}\n";
        break;
    case TransferStatus::CANCELLED:
        // Transfer was cancelled
        break;
}
```

### 4. Use External References

```php
// Use external references to track transfers in your system
$transfer = Asaas::transfers()->create([
    'value' => 300.00,
    'pixAddressKey' => 'user@example.com',
    'pixAddressKeyType' => 'EMAIL',
    'externalReference' => 'ORDER-12345', // Your internal reference
    'description' => 'Payment for order #12345'
]);

// Later, you can search by external reference in your logs
echo "Transfer for order: {$transfer->externalReference}\n";
```

### 5. Use Pagination for Large Lists

```php
$offset = 0;
$limit = 100;

do {
    $transfers = Asaas::transfers()->list([
        'offset' => $offset,
        'limit' => $limit,
        'status' => 'DONE'
    ]);
    
    foreach ($transfers->data as $transfer) {
        // Process each transfer
        echo "Processed transfer: {$transfer->id}\n";
    }
    
    $offset += $limit;
} while ($transfers->hasMore);
```

## Bank Codes Reference

Common Brazilian bank codes for TED transfers:

| Bank | Code | Name |
|------|------|------|
| Banco do Brasil | 001 | Banco do Brasil S.A. |
| Santander | 033 | Banco Santander (Brasil) S.A. |
| Caixa Econômica Federal | 104 | Caixa Econômica Federal |
| Bradesco | 237 | Banco Bradesco S.A. |
| Itaú | 341 | Banco Itaú S.A. |
| Banco Inter | 077 | Banco Inter S.A. |
| Nubank | 260 | Nu Pagamentos S.A. |

## Related Documentation

- [Payment Resource](PAYMENTS.md) - For payment processing
- [Customer Resource](CUSTOMERS.md) - For customer management
- [Entity Reference](ENTITIES.md) - Complete entity documentation
- [Error Handling](ERROR_HANDLING.md) - Error handling guide
