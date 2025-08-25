# Entity Reference Guide

This guide provides comprehensive information about all entities available in the Asaas SDK and demonstrates the three different ways to instantiate and use them.

## Table of Contents

- [Entity Instantiation Patterns](#entity-instantiation-patterns)
- [Customer Entities](#customer-entities)
- [Payment Entities](#payment-entities)
- [Subscription Entities](#subscription-entities)
- [Credit Card Entities](#credit-card-entities)
- [Common Entities](#common-entities)
- [Response Entities](#response-entities)
- [Working with Arrays](#working-with-arrays)
- [Best Practices](#best-practices)

## Entity Instantiation Patterns

All entities in the Asaas SDK support three instantiation patterns:

### 1. Constructor Pattern (`new`)

```php
use Leopaulo88\Asaas\Entities\Customer\CustomerCreate;

$customer = new CustomerCreate(
    name: 'John Doe',
    email: 'john@example.com',
    cpfCnpj: '12345678901'
);
```

### 2. Static Factory Pattern (`make()`)

```php
$customer = CustomerCreateEntity::make()
    ->name('John Doe')
    ->email('john@example.com')
    ->cpfCnpj('12345678901')
    ->phone('11999999999');
```

### 3. Array Hydration Pattern (`fromArray()`)

```php
$customer = CustomerCreateEntity::fromArray([
    'name' => 'John Doe',
    'email' => 'john@example.com',
    'cpfCnpj' => '12345678901',
    'phone' => '11999999999'
]);
```

## Customer Entities

### CustomerCreateEntity

Used for creating new customers.

```php
// Constructor
$customer = new CustomerCreateEntity(
    name: 'Jane Smith',
    email: 'jane@example.com',
    cpfCnpj: '98765432100',
    personType: 'FISICA'
);

// Fluent
$customer = CustomerCreateEntity::make()
    ->name('Jane Smith')
    ->email('jane@example.com')
    ->cpfCnpj('98765432100')
    ->personType('FISICA')
    ->address('123 Main Street')
    ->addressNumber('123')
    ->postalCode('12345678')
    ->phone('11999999999')
    ->observations('VIP Customer');

// From Array
$customer = CustomerCreateEntity::fromArray([
    'name' => 'Jane Smith',
    'email' => 'jane@example.com',
    'cpfCnpj' => '98765432100',
    'personType' => 'FISICA',
    'address' => '123 Main Street',
    'addressNumber' => '123',
    'postalCode' => '12345678'
]);
```

### CustomerUpdateEntity

Used for updating existing customers.

```php
// Constructor
$update = new CustomerUpdateEntity(
    name: 'Jane Smith Updated',
    email: 'jane.updated@example.com'
);

// Fluent
$update = CustomerUpdateEntity::make()
    ->name('Jane Smith Updated')
    ->email('jane.updated@example.com')
    ->phone('11888888888')
    ->observations('Updated information');

// From Array
$update = CustomerUpdateEntity::fromArray([
    'name' => 'Jane Smith Updated',
    'email' => 'jane.updated@example.com',
    'phone' => '11888888888'
]);
```

### CustomerResponse

Response entity (read-only, used for API responses).

```php
// This entity is automatically created from API responses
$customer = Asaas::customers()->find('cus_123');

echo $customer->name; // "Jane Smith"
echo $customer->email; // "jane@example.com"
echo $customer->dateCreated->format('Y-m-d'); // "2025-01-15"
```

## Payment Entities

### PaymentCreate

Used for creating new payments.

```php
// Constructor
$payment = new PaymentCreate(
    customer: 'cus_123456789',
    billingType: BillingType::BOLETO,
    value: 150.00,
    dueDate: '2025-12-31'
);

// Fluent
$payment = PaymentCreate::make()
    ->customer('cus_123456789')
    ->billingType(BillingType::CREDIT_CARD)
    ->value(299.90)
    ->dueDate('2025-12-31')
    ->description('Monthly subscription')
    ->installmentCount(3)
    ->discount(Discount::make()->value(10.00)->dueDateLimitDays(5))
    ->fine(Fine::make()->value(2.50)->type(FineType::FIXED));

// From Array
$payment = PaymentCreate::fromArray([
    'customer' => 'cus_123456789',
    'billingType' => 'PIX',
    'value' => 199.90,
    'dueDate' => '2025-12-31',
    'description' => 'Product purchase',
    'discount' => [
        'value' => 15.00,
        'dueDateLimitDays' => 7
    ]
]);
```

### PaymentUpdate

Used for updating existing payments.

```php
// Constructor
$update = new PaymentUpdate(
    value: 175.00,
    description: 'Updated payment'
);

// Fluent
$update = PaymentUpdate::make()
    ->value(225.50)
    ->dueDate('2025-01-30')
    ->description('Updated payment description')
    ->externalReference('updated_ref_001');

// From Array
$update = PaymentUpdate::fromArray([
    'value' => 199.99,
    'description' => 'Final payment amount',
    'dueDate' => '2025-02-15'
]);
```

### PaymentCreditCard

Used for credit card payment information.

```php
// Constructor
$creditCard = new PaymentCreditCard(
    holderName: 'John Doe',
    number: '4111111111111111',
    expiryMonth: '12',
    expiryYear: '2028',
    ccv: '123'
);

// Fluent
$creditCard = PaymentCreditCard::make()
    ->holderName('John Doe')
    ->number('4111111111111111')
    ->expiryMonth('12')
    ->expiryYear('2028')
    ->ccv('123');

// From Array
$creditCard = PaymentCreditCard::fromArray([
    'holderName' => 'John Doe',
    'number' => '4111111111111111',
    'expiryMonth' => '12',
    'expiryYear' => '2028',
    'ccv' => '123'
]);
```

## Subscription Entities

### SubscriptionCreate

Used for creating new subscriptions.

```php
// Constructor
$subscription = new SubscriptionCreate(
    customer: 'cus_123456789',
    billingType: BillingType::BOLETO,
    value: 99.90,
    nextDueDate: Carbon::parse('2025-02-15'),
    cycle: SubscriptionCycle::MONTHLY
);

// Fluent
$subscription = SubscriptionCreate::make()
    ->customer('cus_123456789')
    ->billingType(BillingType::CREDIT_CARD)
    ->value(149.90)
    ->nextDueDate(Carbon::now()->addMonth())
    ->cycle(SubscriptionCycle::MONTHLY)
    ->description('Premium Plan')
    ->maxPayments(12)
    ->endDate(Carbon::now()->addYear());

// From Array
$subscription = SubscriptionCreate::fromArray([
    'customer' => 'cus_123456789',
    'billingType' => 'PIX',
    'value' => 79.90,
    'nextDueDate' => '2025-02-15',
    'cycle' => 'WEEKLY',
    'description' => 'Weekly Training Sessions'
]);
```

### SubscriptionUpdate

Used for updating existing subscriptions.

```php
// Constructor
$update = new SubscriptionUpdate(
    value: 119.90,
    description: 'Updated Premium Plan'
);

// Fluent
$update = SubscriptionUpdate::make()
    ->value(159.90)
    ->cycle(SubscriptionCycle::QUARTERLY)
    ->description('Quarterly Premium Plan')
    ->nextDueDate(Carbon::now()->addQuarter());

// From Array
$update = SubscriptionUpdate::fromArray([
    'value' => 199.90,
    'description' => 'Enterprise Plan',
    'cycle' => 'YEARLY'
]);
```

## Credit Card Entities

### CreditCardTokenCreate

Used for tokenizing credit cards.

```php
// Constructor
$tokenRequest = new CreditCardTokenCreate(
    customer: 'cus_123456789',
    creditCard: CreditCard::make()
        ->holderName('John Doe')
        ->number('4111111111111111')
        ->expiryMonth('12')
        ->expiryYear('2028')
        ->ccv('123')
);

// Fluent
$tokenRequest = CreditCardTokenCreate::make()
    ->customer('cus_123456789')
    ->creditCard([
        'holderName' => 'John Doe',
        'number' => '4111111111111111',
        'expiryMonth' => '12',
        'expiryYear' => '2028',
        'ccv' => '123'
    ])
    ->remoteIp('192.168.1.1');

// From Array
$tokenRequest = CreditCardTokenCreate::fromArray([
    'customer' => 'cus_123456789',
    'creditCard' => [
        'holderName' => 'John Doe',
        'number' => '4111111111111111',
        'expiryMonth' => '12',
        'expiryYear' => '2028',
        'ccv' => '123'
    ],
    'remoteIp' => '192.168.1.1'
]);
```

## Common Entities

### CreditCard

Credit card information entity.

```php
// Constructor
$card = new CreditCard(
    holderName: 'John Doe',
    number: '4111111111111111',
    expiryMonth: '12',
    expiryYear: '2028',
    ccv: '123'
);

// Fluent
$card = CreditCard::make()
    ->holderName('John Doe')
    ->number('4111111111111111')
    ->expiryMonth('12')
    ->expiryYear('2028')
    ->ccv('123');

// From Array
$card = CreditCard::fromArray([
    'holderName' => 'John Doe',
    'number' => '4111111111111111',
    'expiryMonth' => '12',
    'expiryYear' => '2028',
    'ccv' => '123'
]);
```

### Discount

Discount configuration entity.

```php
// Constructor
$discount = new Discount(
    value: 10.00,
    dueDateLimitDays: 5,
    type: DiscountType::FIXED
);

// Fluent
$discount = Discount::make()
    ->value(15.00)
    ->dueDateLimitDays(7)
    ->type(DiscountType::PERCENTAGE);

// From Array
$discount = Discount::fromArray([
    'value' => 20.00,
    'dueDateLimitDays' => 10,
    'type' => 'FIXED'
]);
```

### Fine

Fine configuration entity.

```php
// Constructor
$fine = new Fine(
    value: 5.00,
    type: FineType::FIXED
);

// Fluent
$fine = Fine::make()
    ->value(2.5)
    ->type(FineType::PERCENTAGE);

// From Array
$fine = Fine::fromArray([
    'value' => 10.00,
    'type' => 'FIXED'
]);
```

### Interest

Interest configuration entity.

```php
// Constructor
$interest = new Interest(
    value: 1.0,
    type: InterestType::PERCENTAGE
);

// Fluent
$interest = Interest::make()
    ->value(2.5)
    ->type(InterestType::PERCENTAGE);

// From Array
$interest = Interest::fromArray([
    'value' => 1.5,
    'type' => 'PERCENTAGE'
]);
```

### Split

Payment splitting entity.

```php
// Constructor
$split = new Split(
    walletId: 'wallet_123',
    fixedValue: 50.00,
    percentualValue: 25.0
);

// Fluent
$split = Split::make()
    ->walletId('wallet_456')
    ->percentualValue(30.0)
    ->status(SplitStatus::PENDING);

// From Array
$split = Split::fromArray([
    'walletId' => 'wallet_789',
    'fixedValue' => 75.00,
    'status' => 'PENDING'
]);
```

## Response Entities

Response entities are read-only and automatically created from API responses. They cannot be instantiated manually.

### PaymentResponse

```php
$payment = Asaas::payments()->find('pay_123');

// Access properties
echo $payment->id; // "pay_123"
echo $payment->value; // 150.00
echo $payment->status->value; // "PENDING"
echo $payment->dateCreated->format('Y-m-d H:i:s'); // "2025-01-15 10:30:00"

// Check if has refunds
if ($payment->refunds && count($payment->refunds) > 0) {
    foreach ($payment->refunds as $refund) {
        echo "Refund: {$refund->value}\n";
    }
}
```

### SubscriptionResponse

```php
$subscription = Asaas::subscriptions()->find('sub_123');

echo $subscription->id; // "sub_123"
echo $subscription->status->value; // "ACTIVE"
echo $subscription->cycle->value; // "MONTHLY"
echo $subscription->nextDueDate; // "2025-02-15"
```

### ListResponse

```php
$customers = Asaas::customers()->list();

echo $customers->getTotalCount(); // 150
echo $customers->hasMore() ? 'Yes' : 'No'; // "Yes"
echo $customers->count(); // 20 (current page items)

foreach ($customers->getData() as $customer) {
    echo "Customer: {$customer->name}\n";
}
```

## Working with Arrays

### Converting Entities to Arrays

```php
$customer = CustomerCreateEntity::make()
    ->name('John Doe')
    ->email('john@example.com')
    ->cpfCnpj('12345678901');

// Convert to array
$array = $customer->toArray();
// Result: ['name' => 'John Doe', 'email' => 'john@example.com', 'cpfCnpj' => '12345678901']

// Include empty values
$arrayWithEmpty = $customer->toArray(preserveEmpty: true);
```

### Creating Entities from Arrays

```php
$data = [
    'name' => 'Jane Smith',
    'email' => 'jane@example.com',
    'cpfCnpj' => '98765432100',
    'address' => '123 Main St',
    'phone' => '11999999999'
];

$customer = CustomerCreateEntity::fromArray($data);
```

### Nested Entities

```php
$paymentData = [
    'customer' => 'cus_123',
    'billingType' => 'BOLETO',
    'value' => 100.00,
    'discount' => [
        'value' => 10.00,
        'dueDateLimitDays' => 5,
        'type' => 'FIXED'
    ],
    'fine' => [
        'value' => 5.00,
        'type' => 'PERCENTAGE'
    ]
];

$payment = PaymentCreate::fromArray($paymentData);
// The discount and fine will be automatically converted to Discount and Fine entities
```

## Best Practices

### 1. Choose the Right Pattern

```php
// Use constructor for simple, known data
$customer = new CustomerCreateEntity(
    name: 'John Doe',
    email: 'john@example.com'
);

// Use fluent for complex, conditional building
$payment = PaymentCreate::make()
    ->customer('cus_123')
    ->billingType(BillingType::BOLETO)
    ->value(100.00);

if ($hasDiscount) {
    $payment->discount(Discount::make()->value(10.00));
}

// Use fromArray for API data or form submissions
$customer = CustomerCreateEntity::fromArray($request->validated());
```

### 2. Validation Before API Calls

```php
function createPayment(array $data): PaymentResponse
{
    // Validate required fields
    if (!isset($data['customer']) || !isset($data['value'])) {
        throw new InvalidArgumentException('Customer and value are required');
    }
    
    // Create entity
    $payment = PaymentCreate::fromArray($data);
    
    // Additional validation
    if ($payment->value <= 0) {
        throw new InvalidArgumentException('Payment value must be positive');
    }
    
    return Asaas::payments()->create($payment);
}
```

### 3. Entity Factories for Testing

```php
class EntityFactory
{
    public static function customer(array $overrides = []): CustomerCreateEntity
    {
        $defaults = [
            'name' => 'Test Customer',
            'email' => 'test@example.com',
            'cpfCnpj' => '12345678901',
            'personType' => 'FISICA'
        ];
        
        return CustomerCreateEntity::fromArray(array_merge($defaults, $overrides));
    }
    
    public static function payment(array $overrides = []): PaymentCreate
    {
        $defaults = [
            'customer' => 'cus_test',
            'billingType' => 'BOLETO',
            'value' => 100.00,
            'dueDate' => '2025-12-31'
        ];
        
        return PaymentCreate::fromArray(array_merge($defaults, $overrides));
    }
}

// Usage in tests
$customer = EntityFactory::customer(['name' => 'Specific Test Name']);
$payment = EntityFactory::payment(['value' => 250.00]);
```

### 4. Entity Builders for Complex Scenarios

```php
class PaymentBuilder
{
    private array $data = [];
    
    public function customer(string $customerId): self
    {
        $this->data['customer'] = $customerId;
        return $this;
    }
    
    public function boleto(float $value, string $dueDate): self
    {
        $this->data['billingType'] = 'BOLETO';
        $this->data['value'] = $value;
        $this->data['dueDate'] = $dueDate;
        return $this;
    }
    
    public function creditCard(float $value, int $installments = 1): self
    {
        $this->data['billingType'] = 'CREDIT_CARD';
        $this->data['value'] = $value;
        if ($installments > 1) {
            $this->data['installmentCount'] = $installments;
            $this->data['installmentValue'] = $value / $installments;
        }
        return $this;
    }
    
    public function withDiscount(float $amount, int $days = 5): self
    {
        $this->data['discount'] = [
            'value' => $amount,
            'dueDateLimitDays' => $days,
            'type' => 'FIXED'
        ];
        return $this;
    }
    
    public function build(): PaymentCreate
    {
        return PaymentCreate::fromArray($this->data);
    }
}

// Usage
$payment = (new PaymentBuilder())
    ->customer('cus_123')
    ->creditCard(300.00, 3)
    ->withDiscount(30.00, 7)
    ->build();
```
