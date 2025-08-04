# Payment Resource

The Payment Resource allows you to manage payments and charges in your Asaas account. You can create payments with different billing types, update them, capture authorized payments, and handle refunds.

## Table of Contents

- [Creating Payments](#creating-payments)
- [Listing Payments](#listing-payments)
- [Finding a Payment](#finding-a-payment)
- [Updating Payments](#updating-payments)
- [Deleting Payments](#deleting-payments)
- [Restoring Payments](#restoring-payments)
- [Credit Card Payments](#credit-card-payments)
- [Capturing Authorized Payments](#capturing-authorized-payments)
- [Payment Information](#payment-information)
- [Refunds](#refunds)
- [Entity Reference](#entity-reference)

## Creating Payments

### Basic Payment (Boleto)

```php
use Leopaulo88\Asaas\Facades\Asaas;

$payment = Asaas::payments()->create([
    'customer' => 'cus_123456789',
    'billingType' => 'BOLETO',
    'value' => 150.00,
    'dueDate' => '2025-12-31',
    'description' => 'Monthly subscription payment'
]);
```

### PIX Payment

```php
$payment = Asaas::payments()->create([
    'customer' => 'cus_123456789',
    'billingType' => 'PIX',
    'value' => 250.50,
    'dueDate' => '2025-02-15',
    'description' => 'Product purchase'
]);
```

### Get Pix QR Code for Payment

```php
$pixQrCode = Asaas::payments()->pixQrCode('pay_123456789');

echo "Base64 Image: {$pixQrCode->encodedImage}\n";
echo "Payload: {$pixQrCode->payload}\n";
echo "Expiration Date: {$pixQrCode->expirationDate}\n";
```

### Using Entity

```php
use Leopaulo88\Asaas\Entities\Payment\PaymentCreate;

$paymentData = PaymentCreate::make()
    ->customer('cus_123456789')
    ->billingType('CREDIT_CARD')
    ->value(99.90)
    ->dueDate('2025-03-20')
    ->description('Service payment')
    ->externalReference('invoice_001');

$payment = Asaas::payments()->create($paymentData);
```

### Payment with Installments

```php
$payment = Asaas::payments()->create([
    'customer' => 'cus_123456789',
    'billingType' => 'CREDIT_CARD',
    'value' => 300.00,
    'installmentCount' => 3,
    'installmentValue' => 100.00,
    'dueDate' => '2025-02-15',
    'description' => 'Product in 3 installments'
]);
```

### Payment with Discount and Fine

```php
$payment = Asaas::payments()->create([
    'customer' => 'cus_123456789',
    'billingType' => 'BOLETO',
    'value' => 200.00,
    'dueDate' => '2025-03-01',
    'discount' => [
        'value' => 10.00,
        'dueDateLimitDays' => 5
    ],
    'fine' => [
        'value' => 2.50,
        'type' => 'FIXED'
    ],
    'interest' => [
        'value' => 1.0,
        'type' => 'PERCENTAGE'
    ]
]);
```

## Listing Payments

### Basic List

```php
$payments = Asaas::payments()->list();

foreach ($payments->getData() as $payment) {
    echo "Payment {$payment->id}: {$payment->value} - {$payment->status->value}\n";
}
```

### With Filters

```php
$payments = Asaas::payments()->list([
    'customer' => 'cus_123456789',
    'status' => 'PENDING',
    'billingType' => 'BOLETO',
    'subscription' => 'sub_987654321',
    'paymentDate[ge]' => '2025-01-01',
    'paymentDate[le]' => '2025-12-31',
    'dueDate[ge]' => '2025-02-01',
    'value[ge]' => 100.00,
    'limit' => 50,
    'offset' => 0
]);
```

### Filter by Date Range

```php
$payments = Asaas::payments()->list([
    'dateCreated[ge]' => '2025-01-01',
    'dateCreated[le]' => '2025-01-31',
    'status' => 'RECEIVED'
]);
```

## Finding a Payment

```php
$payment = Asaas::payments()->find('pay_123456789');

echo "Payment: {$payment->id}\n";
echo "Customer: {$payment->customer}\n";
echo "Value: R$ {$payment->value}\n";
echo "Status: {$payment->status->value}\n";
echo "Due Date: {$payment->dueDate}\n";
```

## Updating Payments

### Basic Update

```php
$payment = Asaas::payments()->update('pay_123456789', [
    'description' => 'Updated payment description',
    'value' => 175.00,
    'dueDate' => '2025-04-01'
]);
```

### Using Entity

```php
use Leopaulo88\Asaas\Entities\Payment\PaymentUpdate;

$updateData = PaymentUpdate::make()
    ->description('Updated via entity')
    ->value(225.50)
    ->externalReference('updated_invoice_001');

$payment = Asaas::payments()->update('pay_123456789', $updateData);
```

## Deleting Payments

```php
$result = Asaas::payments()->delete('pay_123456789');

if ($result->deleted) {
    echo "Payment {$result->id} was successfully deleted\n";
}
```

## Restoring Payments

```php
$payment = Asaas::payments()->restore('pay_123456789');

echo "Payment restored: {$payment->id}\n";
echo "Status: {$payment->status->value}\n";
```

## Credit Card Payments

### Pay with Credit Card Data

```php
$payment = Asaas::payments()->payWithCreditCard('pay_123456789', [
    'holderName' => 'John Doe',
    'number' => '4111111111111111',
    'expiryMonth' => '12',
    'expiryYear' => '2028',
    'ccv' => '123'
]);
```

### Pay with Credit Card Entity

```php
use Leopaulo88\Asaas\Entities\Payment\PaymentCreditCard;

$creditCard = PaymentCreditCard::make()
    ->holderName('John Doe')
    ->number('4111111111111111')
    ->expiryMonth('12')
    ->expiryYear('2028')
    ->ccv('123');

$payment = Asaas::payments()->payWithCreditCard('pay_123456789', $creditCard);
```

### Pay with Credit Card Holder Info

```php
$payment = Asaas::payments()->payWithCreditCard('pay_123456789', [
    'holderName' => 'John Doe',
    'number' => '4111111111111111',
    'expiryMonth' => '12',
    'expiryYear' => '2028',
    'ccv' => '123',
    'creditCardHolderInfo' => [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'cpfCnpj' => '12345678901',
        'postalCode' => '12345678',
        'addressNumber' => '123',
        'phone' => '11999999999'
    ]
]);
```

## Capturing Authorized Payments

```php
$payment = Asaas::payments()->captureAuthorizedPayment('pay_123456789');

echo "Payment captured: {$payment->id}\n";
echo "New status: {$payment->status->value}\n";
```

## Payment Information

### Get Billing Information

```php
$billingInfo = Asaas::payments()->billingInfo('pay_123456789');

echo "Customer Email: {$billingInfo->email}\n";
echo "Customer Phone: {$billingInfo->phone}\n";
echo "Customer Name: {$billingInfo->name}\n";
```

### Get Payment Status

```php
$status = Asaas::payments()->status('pay_123456789');

echo "Status: {$status->status}\n";
echo "Nostro Number: {$status->nostroNumber}\n";
```

## Refunds

### Full Refund

```php
$payment = Asaas::payments()->refund('pay_123456789', [
    'value' => null, // null for full refund
    'description' => 'Customer requested refund'
]);
```

### Partial Refund

```php
$payment = Asaas::payments()->refund('pay_123456789', [
    'value' => 50.00,
    'description' => 'Partial refund due to product return'
]);
```

## Entity Reference

### PaymentCreate

Properties available for payment creation:

```php
public ?string $customer = null;
public ?BillingType $billingType = null;
public ?float $value = null;
public ?string $dueDate = null;
public ?string $description = null;
public ?int $installmentCount = null;
public ?float $installmentValue = null;
public ?Discount $discount = null;
public ?Interest $interest = null;
public ?Fine $fine = null;
public ?bool $postalService = null;
public ?array $split = null; // Split[]
public ?CreditCard $creditCard = null;
public ?CreditCardHolderInfo $creditCardHolderInfo = null;
public ?string $creditCardToken = null;
public ?string $externalReference = null;
public ?Callback $callback = null;
```

### PaymentUpdate

Properties available for payment updates:

```php
public ?float $value = null;
public ?string $dueDate = null;
public ?string $description = null;
public ?Discount $discount = null;
public ?Interest $interest = null;
public ?Fine $fine = null;
public ?string $externalReference = null;
```

### Pix
```php
public ?string $encodedImage;
public ?string $payload;
public ?string $expirationDate;
```

### PaymentResponse

Response entity with complete payment data:

```php
public ?string $object;
public ?string $id;
public ?Carbon $dateCreated;
public ?string $customer;
public ?string $subscription;
public ?string $installment;
public ?PaymentStatus $status;
public ?float $value;
public ?float $netValue;
public ?float $originalValue;
public ?float $interestValue;
public ?string $description;
public ?BillingType $billingType;
public ?bool $confirmedDate;
public ?string $pixTransaction;
public ?string $originalDueDate;
public ?string $paymentDate;
public ?string $clientPaymentDate;
public ?string $installmentNumber;
public ?string $creditDate;
public ?string $estimatedCreditDate;
public ?string $invoiceUrl;
public ?string $bankSlipUrl;
public ?string $transactionReceiptUrl;
public ?string $invoiceNumber;
public ?string $externalReference;
public ?bool $deleted;
public ?bool $anticipated;
public ?bool $anticipable;
public ?CreditCard $creditCard;
public ?Discount $discount;
public ?Fine $fine;
public ?Interest $interest;
public ?bool $postalService;
public ?array $split; // Split[]
public ?Chargeback $chargeback;
public ?array $refunds; // Refund[]
```

## Billing Types

- **BOLETO** - Bank slip payment
- **CREDIT_CARD** - Credit card payment
- **DEBIT_CARD** - Debit card payment
- **PIX** - Instant payment method
- **UNDEFINED** - Undefined payment method

## Payment Status

- **PENDING** - Waiting for payment
- **RECEIVED** - Payment received
- **CONFIRMED** - Payment confirmed
- **OVERDUE** - Payment overdue
- **REFUNDED** - Payment refunded
- **RECEIVED_IN_CASH** - Received in cash
- **REFUND_REQUESTED** - Refund requested
- **REFUND_IN_PROGRESS** - Refund in progress
- **CHARGEBACK_REQUESTED** - Chargeback requested
- **CHARGEBACK_DISPUTE** - Chargeback dispute
- **AWAITING_CHARGEBACK_REVERSAL** - Awaiting chargeback reversal
- **DUNNING_REQUESTED** - Dunning requested
- **DUNNING_RECEIVED** - Dunning received
- **AWAITING_RISK_ANALYSIS** - Awaiting risk analysis

## Error Handling

```php
use Leopaulo88\Asaas\Exceptions\{BadRequestException, NotFoundException};

try {
    $payment = Asaas::payments()->create([
        'customer' => 'invalid_customer',
        'billingType' => 'BOLETO',
        'value' => 0 // Invalid value
    ]);
} catch (BadRequestException $e) {
    echo "Validation error: " . $e->getMessage() . "\n";
    foreach ($e->getErrors() as $field => $errors) {
        echo "Field {$field}: " . implode(', ', $errors) . "\n";
    }
} catch (NotFoundException $e) {
    echo "Payment not found: " . $e->getMessage() . "\n";
}
```

## Examples

### Using Different API Keys

```php
// Use different API key for specific tenant payment
$payment = Asaas::withApiKey($tenant->asaas_api_key)
    ->payments()
    ->create([
        'customer' => 'cus_123456789',
        'billingType' => 'BOLETO',
        'value' => 150.00,
        'dueDate' => '2025-12-31'
    ]);

// Use production API key for specific operation
$payment = Asaas::withApiKey('production_key', 'production')
    ->payments()
    ->find('pay_123456789');
```

### Creating Recurring Payment Structure

```php
// Create customer first
$customer = Asaas::customers()->create([
    'name' => 'Monthly Subscriber',
    'email' => 'subscriber@example.com',
    'cpfCnpj' => '12345678901'
]);

// Create monthly payments for the year
$baseDate = Carbon::now()->startOfMonth();

for ($month = 1; $month <= 12; $month++) {
    $dueDate = $baseDate->copy()->addMonths($month);
    
    $payment = Asaas::payments()->create([
        'customer' => $customer->id,
        'billingType' => 'BOLETO',
        'value' => 99.90,
        'dueDate' => $dueDate->format('Y-m-d'),
        'description' => "Monthly subscription - {$dueDate->format('M Y')}",
        'externalReference' => "subscription_{$customer->id}_{$month}"
    ]);
    
    echo "Created payment for {$dueDate->format('M Y')}: {$payment->id}\n";
}
```
