# Subscription Resource

The Subscription Resource allows you to manage recurring subscriptions in your Asaas account. You can create subscriptions with different billing cycles, update them, manage credit cards, and list associated payments.

## Table of Contents

- [Creating Subscriptions](#creating-subscriptions)
- [Listing Subscriptions](#listing-subscriptions)
- [Finding a Subscription](#finding-a-subscription)
- [Updating Subscriptions](#updating-subscriptions)
- [Deleting Subscriptions](#deleting-subscriptions)
- [Managing Credit Cards](#managing-credit-cards)
- [Listing Subscription Payments](#listing-subscription-payments)
- [Entity Reference](#entity-reference)

## Creating Subscriptions

### Basic Monthly Subscription

```php
use Leopaulo88\Asaas\Facades\Asaas;

$subscription = Asaas::subscriptions()->create([
    'customer' => 'cus_123456789',
    'billingType' => 'BOLETO',
    'value' => 99.90,
    'nextDueDate' => '2025-02-15',
    'cycle' => 'MONTHLY',
    'description' => 'Monthly Premium Plan'
]);
```

### Weekly Subscription

```php
$subscription = Asaas::subscriptions()->create([
    'customer' => 'cus_123456789',
    'billingType' => 'PIX',
    'value' => 29.90,
    'nextDueDate' => '2025-02-08',
    'cycle' => 'WEEKLY',
    'description' => 'Weekly Training Sessions'
]);
```

### Using Entity

```php
use Leopaulo88\Asaas\Entities\Subscription\SubscriptionCreate;

$subscriptionData = SubscriptionCreate::make()
    ->customer('cus_123456789')
    ->billingType('CREDIT_CARD')
    ->value(149.90)
    ->nextDueDate(Carbon::now()->addMonth())
    ->cycle('MONTHLY')
    ->description('Premium Monthly Subscription')
    ->externalReference('plan_premium_001');

$subscription = Asaas::subscriptions()->create($subscriptionData);
```

### Subscription with End Date and Max Payments

```php
$subscription = Asaas::subscriptions()->create([
    'customer' => 'cus_123456789',
    'billingType' => 'BOLETO',
    'value' => 199.90,
    'nextDueDate' => '2025-02-01',
    'cycle' => 'MONTHLY',
    'endDate' => '2025-12-31',
    'maxPayments' => 12,
    'description' => '12-month subscription plan'
]);
```

### Subscription with Discount and Fine

```php
$subscription = Asaas::subscriptions()->create([
    'customer' => 'cus_123456789',
    'billingType' => 'BOLETO',
    'value' => 299.90,
    'nextDueDate' => '2025-03-01',
    'cycle' => 'QUARTERLY',
    'discount' => [
        'value' => 30.00,
        'dueDateLimitDays' => 10,
        'type' => 'FIXED'
    ],
    'fine' => [
        'value' => 5.00,
        'type' => 'FIXED'
    ],
    'interest' => [
        'value' => 2.0,
        'type' => 'PERCENTAGE'
    ]
]);
```

## Listing Subscriptions

### Basic List

```php
$subscriptions = Asaas::subscriptions()->list();

foreach ($subscriptions->getData() as $subscription) {
    echo "Subscription {$subscription->id}: {$subscription->value} - {$subscription->status->value}\n";
}
```

### With Filters

```php
$subscriptions = Asaas::subscriptions()->list([
    'customer' => 'cus_123456789',
    'status' => 'ACTIVE',
    'billingType' => 'CREDIT_CARD',
    'cycle' => 'MONTHLY',
    'dateCreated[ge]' => '2025-01-01',
    'dateCreated[le]' => '2025-12-31',
    'limit' => 50,
    'offset' => 0
]);
```

### Filter by Status and Billing Type

```php
$activeSubscriptions = Asaas::subscriptions()->list([
    'status' => 'ACTIVE',
    'billingType' => 'BOLETO'
]);

$expiredSubscriptions = Asaas::subscriptions()->list([
    'status' => 'EXPIRED'
]);
```

## Finding a Subscription

```php
$subscription = Asaas::subscriptions()->find('sub_123456789');

echo "Subscription: {$subscription->id}\n";
echo "Customer: {$subscription->customer}\n";
echo "Value: R$ {$subscription->value}\n";
echo "Status: {$subscription->status->value}\n";
echo "Next Due Date: {$subscription->nextDueDate}\n";
echo "Cycle: {$subscription->cycle->value}\n";
```

## Updating Subscriptions

### Basic Update

```php
$subscription = Asaas::subscriptions()->update('sub_123456789', [
    'description' => 'Updated Premium Plan',
    'value' => 119.90,
    'nextDueDate' => '2025-03-15'
]);
```

### Using Entity

```php
use Leopaulo88\Asaas\Entities\Subscription\SubscriptionUpdate;

$updateData = SubscriptionUpdate::make()
    ->description('Updated via entity')
    ->value(179.90)
    ->cycle('QUARTERLY')
    ->externalReference('updated_plan_001');

$subscription = Asaas::subscriptions()->update('sub_123456789', $updateData);
```

### Update Billing Configuration

```php
$subscription = Asaas::subscriptions()->update('sub_123456789', [
    'value' => 249.90,
    'discount' => [
        'value' => 25.00,
        'dueDateLimitDays' => 5
    ],
    'fine' => [
        'value' => 10.00,
        'type' => 'PERCENTAGE'
    ]
]);
```

## Deleting Subscriptions

```php
$result = Asaas::subscriptions()->delete('sub_123456789');

if ($result->deleted) {
    echo "Subscription {$result->id} was successfully deleted\n";
}
```

## Managing Credit Cards

### Update Credit Card

```php
$subscription = Asaas::subscriptions()->updateCreditCard('sub_123456789', [
    'holderName' => 'John Doe',
    'number' => '4111111111111111',
    'expiryMonth' => '12',
    'expiryYear' => '2028',
    'ccv' => '123'
]);

echo "Credit card updated for subscription: {$subscription->id}\n";
```

### Using Entity for Credit Card Update

```php
use Leopaulo88\Asaas\Entities\Subscription\SubscriptionUpdateCreditCard;

$creditCardData = SubscriptionUpdateCreditCard::make()
    ->holderName('Jane Smith')
    ->number('5555555555554444')
    ->expiryMonth('06')
    ->expiryYear('2029')
    ->ccv('456');

$subscription = Asaas::subscriptions()->updateCreditCard('sub_123456789', $creditCardData);
```

### Update Credit Card with Holder Info

```php
$subscription = Asaas::subscriptions()->updateCreditCard('sub_123456789', [
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

## Listing Subscription Payments

### All Payments from Subscription

```php
$payments = Asaas::subscriptions()->listPayments('sub_123456789');

foreach ($payments->getData() as $payment) {
    echo "Payment {$payment->id}: R$ {$payment->value} - {$payment->status->value}\n";
}
```

### Filter Payments by Status

```php
$paidPayments = Asaas::subscriptions()->listPayments('sub_123456789', [
    'status' => 'RECEIVED'
]);

$pendingPayments = Asaas::subscriptions()->listPayments('sub_123456789', [
    'status' => 'PENDING'
]);
```

### Payments with Date Range

```php
$payments = Asaas::subscriptions()->listPayments('sub_123456789', [
    'paymentDate[ge]' => '2025-01-01',
    'paymentDate[le]' => '2025-01-31',
    'limit' => 20
]);
```

## Entity Reference

### SubscriptionCreate

Properties available for subscription creation:

```php
public ?string $customer = null;
public ?BillingType $billingType = null;
public ?float $value = null;
public ?Carbon $nextDueDate = null;
public ?Discount $discount = null;
public ?Interest $interest = null;
public ?Fine $fine = null;
public ?SubscriptionCycle $cycle = null;
public ?string $description = null;
public ?Carbon $endDate = null;
public ?int $maxPayments = null;
public ?string $externalReference = null;
public ?array $split = null; // Split[]
public ?CreditCard $creditCard = null;
public ?CreditCardHolderInfo $creditCardHolderInfo = null;
public ?string $creditCardToken = null;
```

### SubscriptionUpdate

Properties available for subscription updates:

```php
public ?BillingType $billingType = null;
public ?float $value = null;
public ?Carbon $nextDueDate = null;
public ?Discount $discount = null;
public ?Interest $interest = null;
public ?Fine $fine = null;
public ?SubscriptionCycle $cycle = null;
public ?string $description = null;
public ?Carbon $endDate = null;
public ?int $maxPayments = null;
public ?string $externalReference = null;
public ?array $split = null; // Split[]
public ?CreditCard $creditCard = null;
public ?CreditCardHolderInfo $creditCardHolderInfo = null;
public ?string $creditCardToken = null;
```

### SubscriptionResponse

Response entity with complete subscription data:

```php
public ?string $object;
public ?string $id;
public ?Carbon $dateCreated;
public ?string $customer;
public ?BillingType $billingType;
public ?SubscriptionCycle $cycle;
public ?float $value;
public ?Carbon $nextDueDate;
public ?string $description;
public ?Carbon $endDate;
public ?int $maxPayments;
public ?SubscriptionStatus $status;
public ?string $externalReference;
public ?array $split; // Split[]
public ?Discount $discount;
public ?Interest $interest;
public ?Fine $fine;
public ?bool $deleted;
```

## Subscription Cycles

- **WEEKLY** - Weekly billing
- **BIWEEKLY** - Every two weeks
- **MONTHLY** - Monthly billing
- **BIMONTHLY** - Every two months
- **QUARTERLY** - Every three months
- **SEMIANNUALLY** - Every six months
- **YEARLY** - Annual billing

## Subscription Status

- **ACTIVE** - Active subscription
- **EXPIRED** - Expired subscription
- **INACTIVE** - Inactive subscription

## Error Handling

```php
use Leopaulo88\Asaas\Exceptions\{BadRequestException, NotFoundException};

try {
    $subscription = Asaas::subscriptions()->create([
        'customer' => 'invalid_customer',
        'billingType' => 'BOLETO',
        'value' => 0, // Invalid value
        'cycle' => 'INVALID_CYCLE'
    ]);
} catch (BadRequestException $e) {
    echo "Validation error: " . $e->getMessage() . "\n";
    foreach ($e->getErrors() as $field => $errors) {
        echo "Field {$field}: " . implode(', ', $errors) . "\n";
    }
} catch (NotFoundException $e) {
    echo "Subscription not found: " . $e->getMessage() . "\n";
}
```

## Examples

### Using Different API Keys

```php
// Use different API key for specific tenant subscription
$subscription = Asaas::withApiKey($tenant->asaas_api_key)
    ->subscriptions()
    ->create([
        'customer' => 'cus_123456789',
        'billingType' => 'BOLETO',
        'value' => 99.90,
        'nextDueDate' => '2025-02-15',
        'cycle' => 'MONTHLY'
    ]);

// Use production API key for specific operation
$subscription = Asaas::withApiKey('production_key', 'production')
    ->subscriptions()
    ->find('sub_123456789');
```

### Creating Different Subscription Plans

```php
// Basic Plan - Monthly
$basicPlan = Asaas::subscriptions()->create([
    'customer' => $customer->id,
    'billingType' => 'BOLETO',
    'value' => 29.90,
    'nextDueDate' => '2025-02-01',
    'cycle' => 'MONTHLY',
    'description' => 'Basic Plan - Monthly',
    'externalReference' => 'basic_monthly'
]);

// Premium Plan - Quarterly with Discount
$premiumPlan = Asaas::subscriptions()->create([
    'customer' => $customer->id,
    'billingType' => 'CREDIT_CARD',
    'value' => 249.90,
    'nextDueDate' => '2025-02-01',
    'cycle' => 'QUARTERLY',
    'description' => 'Premium Plan - Quarterly',
    'discount' => [
        'value' => 25.00,
        'dueDateLimitDays' => 7
    ],
    'externalReference' => 'premium_quarterly'
]);

// Annual Plan with Max Payments
$annualPlan = Asaas::subscriptions()->create([
    'customer' => $customer->id,
    'billingType' => 'PIX',
    'value' => 599.90,
    'nextDueDate' => '2025-02-01',
    'cycle' => 'YEARLY',
    'maxPayments' => 3, // 3 years maximum
    'description' => 'Enterprise Plan - Yearly (3 years max)',
    'externalReference' => 'enterprise_yearly'
]);
```

### Subscription Management System

```php
class SubscriptionManager
{
    public function upgradeSubscription(string $subscriptionId, string $newPlan)
    {
        $planValues = [
            'basic' => 29.90,
            'premium' => 59.90,
            'enterprise' => 99.90
        ];

        if (!isset($planValues[$newPlan])) {
            throw new InvalidArgumentException('Invalid plan');
        }

        return Asaas::subscriptions()->update($subscriptionId, [
            'value' => $planValues[$newPlan],
            'description' => ucfirst($newPlan) . ' Plan',
            'externalReference' => $newPlan . '_plan'
        ]);
    }

    public function pauseSubscription(string $subscriptionId)
    {
        // In Asaas, you would typically update the next due date to a future date
        $futureDate = Carbon::now()->addYear();
        
        return Asaas::subscriptions()->update($subscriptionId, [
            'nextDueDate' => $futureDate,
            'description' => 'Subscription Paused'
        ]);
    }

    public function getSubscriptionReport(string $customerId)
    {
        $subscriptions = Asaas::subscriptions()->list(['customer' => $customerId]);
        $report = [];

        foreach ($subscriptions->getData() as $subscription) {
            $payments = Asaas::subscriptions()->listPayments($subscription->id);
            
            $report[] = [
                'subscription_id' => $subscription->id,
                'value' => $subscription->value,
                'status' => $subscription->status->value,
                'total_payments' => $payments->getTotalCount(),
                'next_due_date' => $subscription->nextDueDate
            ];
        }

        return $report;
    }
}
```
