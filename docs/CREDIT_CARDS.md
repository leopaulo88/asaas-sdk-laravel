# Credit Card Resource

The Credit Card Resource allows you to securely tokenize credit card information for use in payments and subscriptions. This ensures PCI compliance by handling sensitive card data through Asaas servers.

## Table of Contents

- [Tokenizing Credit Cards](#tokenizing-credit-cards)
- [Entity Reference](#entity-reference)
- [Error Handling](#error-handling)
- [Examples](#examples)

## Tokenizing Credit Cards

### Basic Tokenization

```php
use Leopaulo88\Asaas\Facades\Asaas;

$token = Asaas::creditCards()->tokenize([
    'customer' => 'cus_123456789',
    'creditCard' => [
        'holderName' => 'John Doe',
        'number' => '4111111111111111',
        'expiryMonth' => '12',
        'expiryYear' => '2028',
        'ccv' => '123'
    ]
]);

echo "Token: {$token->creditCardToken}\n";
echo "Masked Number: {$token->creditCardNumber}\n";
echo "Brand: {$token->creditCardBrand}\n";
```

### Using Entity

```php
use Leopaulo88\Asaas\Entities\CreditCardToken\CreditCardTokenCreate;

$tokenRequest = CreditCardTokenCreate::make()
    ->customer('cus_123456789')
    ->creditCard([
        'holderName' => 'Jane Smith',
        'number' => '5555555555554444',
        'expiryMonth' => '06',
        'expiryYear' => '2029',
        'ccv' => '456'
    ]);

$token = Asaas::creditCards()->tokenize($tokenRequest);
```

### With Cardholder Information

```php
$token = Asaas::creditCards()->tokenize([
    'customer' => 'cus_123456789',
    'creditCard' => [
        'holderName' => 'John Doe',
        'number' => '4111111111111111',
        'expiryMonth' => '12',
        'expiryYear' => '2028',
        'ccv' => '123'
    ],
    'creditCardHolderInfo' => [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'cpfCnpj' => '12345678901',
        'postalCode' => '12345678',
        'addressNumber' => '123',
        'addressComplement' => 'Apt 2B',
        'phone' => '11999999999',
        'mobilePhone' => '11888888888'
    ],
    'remoteIp' => '192.168.1.100'
]);
```

### Complete Tokenization with All Data

```php
use Leopaulo88\Asaas\Entities\Common\{CreditCard, CreditCardHolderInfo};

$creditCard = CreditCard::make()
    ->holderName('Maria Silva')
    ->number('378282246310005') // American Express
    ->expiryMonth('03')
    ->expiryYear('2027')
    ->ccv('1234');

$holderInfo = CreditCardHolderInfo::make()
    ->name('Maria Silva')
    ->email('maria@example.com')
    ->cpfCnpj('98765432100')
    ->postalCode('01234567')
    ->addressNumber('456')
    ->addressComplement('Suite 100')
    ->province('Centro')
    ->city('São Paulo')
    ->state('SP')
    ->country('Brasil')
    ->phone('1133334444')
    ->mobilePhone('11999998888');

$tokenRequest = CreditCardTokenCreate::make()
    ->customer('cus_123456789')
    ->creditCard($creditCard)
    ->creditCardHolderInfo($holderInfo)
    ->remoteIp('203.0.113.1');

$token = Asaas::creditCards()->tokenize($tokenRequest);
```

## Using Tokens in Payments

### Create Payment with Token

```php
// First, tokenize the credit card
$token = Asaas::creditCards()->tokenize([
    'customer' => 'cus_123456789',
    'creditCard' => [
        'holderName' => 'John Doe',
        'number' => '4111111111111111',
        'expiryMonth' => '12',
        'expiryYear' => '2028',
        'ccv' => '123'
    ]
]);

// Then create payment using the token
$payment = Asaas::payments()->create([
    'customer' => 'cus_123456789',
    'billingType' => 'CREDIT_CARD',
    'value' => 150.00,
    'dueDate' => '2025-03-15',
    'creditCardToken' => $token->creditCardToken,
    'description' => 'Payment with tokenized card'
]);
```

### Create Subscription with Token

```php
$subscription = Asaas::subscriptions()->create([
    'customer' => 'cus_123456789',
    'billingType' => 'CREDIT_CARD',
    'value' => 99.90,
    'nextDueDate' => '2025-02-15',
    'cycle' => 'MONTHLY',
    'creditCardToken' => $token->creditCardToken,
    'description' => 'Monthly subscription with tokenized card'
]);
```

## Entity Reference

### CreditCardTokenCreate

Properties for credit card tokenization:

```php
public ?string $customer = null;
public ?CreditCard $creditCard = null;
public ?CreditCardHolderInfo $creditCardHolderInfo = null;
public ?string $remoteIp = null;
```

### CreditCard

Credit card information entity:

```php
public ?string $holderName = null;
public ?string $number = null;
public ?string $expiryMonth = null;
public ?string $expiryYear = null;
public ?string $ccv = null;
```

### CreditCardHolderInfo

Cardholder information entity:

```php
public ?string $name = null;
public ?string $email = null;
public ?string $cpfCnpj = null;
public ?string $postalCode = null;
public ?string $addressNumber = null;
public ?string $addressComplement = null;
public ?string $province = null;
public ?string $city = null;
public ?string $state = null;
public ?string $country = null;
public ?string $phone = null;
public ?string $mobilePhone = null;
```

### CreditCardTokenResponse

Response from tokenization:

```php
public ?string $creditCardNumber; // Masked number (e.g., "****1111")
public ?CreditCardBrand $creditCardBrand; // VISA, MASTERCARD, AMEX, etc.
public ?string $creditCardToken; // Token to use in payments
```

## Error Handling

```php
use Leopaulo88\Asaas\Exceptions\{BadRequestException, UnauthorizedException};

try {
    $token = Asaas::creditCards()->tokenize([
        'customer' => 'cus_123456789',
        'creditCard' => [
            'holderName' => 'John Doe',
            'number' => '1234567890123456', // Invalid number
            'expiryMonth' => '13', // Invalid month
            'expiryYear' => '2020', // Expired year
            'ccv' => '12' // Invalid CCV
        ]
    ]);
} catch (BadRequestException $e) {
    echo "Card validation error: " . $e->getMessage() . "\n";
    
    foreach ($e->getErrors() as $field => $errors) {
        echo "Field {$field}: " . implode(', ', $errors) . "\n";
    }
} catch (UnauthorizedException $e) {
    echo "Authentication error: " . $e->getMessage() . "\n";
}
```

## Examples

### Using Different API Keys

```php
// Use different API key for specific tenant
$token = Asaas::withApiKey($tenant->asaas_api_key)
    ->creditCards()
    ->tokenize([
        'customer' => 'cus_123456789',
        'creditCard' => [
            'holderName' => 'John Doe',
            'number' => '4111111111111111',
            'expiryMonth' => '12',
            'expiryYear' => '2028',
            'ccv' => '123'
        ]
    ]);

// Use production API key for tokenization
$token = Asaas::withApiKey('production_key', 'production')
    ->creditCards()
    ->tokenize($creditCardData);
```

### Complete Integration Example

```php
class PaymentController extends Controller
{
    public function processPayment(PaymentRequest $request)
    {
        DB::beginTransaction();
        
        try {
            // Create or get customer
            $customer = $this->getOrCreateCustomer($request->customer_data);
            
            // Tokenize credit card
            $token = Asaas::creditCards()->tokenize([
                'customer' => $customer->asaas_id,
                'creditCard' => [
                    'holderName' => $request->card_holder_name,
                    'number' => $request->card_number,
                    'expiryMonth' => $request->card_expiry_month,
                    'expiryYear' => $request->card_expiry_year,
                    'ccv' => $request->card_ccv
                ],
                'creditCardHolderInfo' => [
                    'name' => $request->customer_data['name'],
                    'email' => $request->customer_data['email'],
                    'cpfCnpj' => $request->customer_data['cpfCnpj'],
                    'phone' => $request->customer_data['phone']
                ],
                'remoteIp' => $request->ip()
            ]);
            
            // Create payment with token
            $payment = Asaas::payments()->create([
                'customer' => $customer->asaas_id,
                'billingType' => 'CREDIT_CARD',
                'value' => $request->amount,
                'dueDate' => now()->addDays(7)->format('Y-m-d'),
                'creditCardToken' => $token->creditCardToken,
                'description' => $request->description,
                'externalReference' => "order_{$request->order_id}"
            ]);
            
            // Save payment record
            $paymentRecord = Payment::create([
                'order_id' => $request->order_id,
                'customer_id' => $customer->id,
                'asaas_payment_id' => $payment->id,
                'amount' => $request->amount,
                'status' => $payment->status->value
            ]);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'payment_id' => $payment->id,
                'status' => $payment->status->value
            ]);
            
        } catch (BadRequestException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Payment validation failed',
                'errors' => $e->getErrors()
            ], 400);
            
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Payment processing failed', [
                'order_id' => $request->order_id,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Payment processing failed'
            ], 500);
        }
    }
}
```
