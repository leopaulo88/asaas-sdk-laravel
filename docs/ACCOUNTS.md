# Account Resource

The Account Resource allows you to retrieve information about your Asaas account, including account details, balance, configuration settings, and also create sub-accounts with webhook configurations.

## Table of Contents

- [Getting Account Information](#getting-account-information)
- [Creating Sub-Accounts](#creating-sub-accounts)
- [Webhook Configuration](#webhook-configuration)
- [Entity Reference](#entity-reference)
- [Best Practices](#best-practices)

## Getting Account Information

### Basic Account Info

```php
use Leopaulo88\Asaas\Facades\Asaas;

$account = Asaas::accounts()->info();

echo "Account ID: {$account->id}\n";
echo "Name: {$account->name}\n";
echo "Email: {$account->email}\n";
echo "Login Email: {$account->loginEmail}\n";
echo "Phone: {$account->phone}\n";
echo "Mobile Phone: {$account->mobilePhone}\n";
echo "Address: {$account->address}\n";
echo "Province: {$account->province}\n";
echo "Postal Code: {$account->postalCode}\n";
```

### Account Status and Verification

```php
$account = Asaas::accounts()->info();

// Check account verification status
if ($account->accountNumber) {
    echo "Account Number: {$account->accountNumber->agency}-{$account->accountNumber->account}\n";
    echo "Bank: {$account->accountNumber->bank}\n";
}

// Check person type
echo "Person Type: {$account->personType->value}\n";
echo "CPF/CNPJ: {$account->cpfCnpj}\n";

// Check if account can receive transfers
if ($account->canReceiveTransfers) {
    echo "Account can receive transfers\n";
} else {
    echo "Account cannot receive transfers yet\n";
}
```

### Using Account Info in Your Application

```php
class AccountService
{
    public function getAccountSummary(): array
    {
        $account = Asaas::accounts()->info();
        
        return [
            'account_id' => $account->id,
            'business_name' => $account->name,
            'email' => $account->email,
            'person_type' => $account->personType->value,
            'can_receive_transfers' => $account->canReceiveTransfers,
            'city' => $account->city,
            'state' => $account->state?->value,
            'postal_code' => $account->postalCode
        ];
    }
    
    public function isAccountVerified(): bool
    {
        $account = Asaas::accounts()->info();
        return $account->accountNumber !== null;
    }
    
    public function getAccountBankInfo(): ?array
    {
        $account = Asaas::accounts()->info();
        
        if (!$account->accountNumber) {
            return null;
        }
        
        return [
            'bank' => $account->accountNumber->bank,
            'agency' => $account->accountNumber->agency,
            'account' => $account->accountNumber->account,
            'account_digit' => $account->accountNumber->accountDigit
        ];
    }
}
```

## Creating Sub-Accounts

### Basic Account Creation

You can create sub-accounts using either array data or the fluent interface:

```php
use Leopaulo88\Asaas\Facades\Asaas;
use Leopaulo88\Asaas\Entities\Account\AccountCreate;

// Using array data
$accountData = [
    'name' => 'João Silva',
    'email' => 'joao@exemplo.com',
    'cpfCnpj' => '12345678901',
    'birthDate' => '1990-01-01',
    'phone' => '11999999999',
    'mobilePhone' => '11888888888',
    'address' => 'Rua das Flores, 123',
    'addressNumber' => '123',
    'complement' => 'Apto 45',
    'province' => 'Centro',
    'postalCode' => '01234-567',
    'companyType' => 'MEI',
    'site' => 'https://joao.com.br',
    'incomeValue' => 5000
];

$account = Asaas::accounts()->create($accountData);

// Using fluent interface
$account = Asaas::accounts()->create(
    (new AccountCreate)
        ->name('Maria Santos')
        ->email('maria@exemplo.com')
        ->cpfCnpj('98765432100')
        ->birthDate('1985-05-15')
        ->phone('11888888888')
        ->address('Av. Paulista, 1000')
        ->addressNumber('1000')
        ->province('Bela Vista')
        ->postalCode('01310-100')
        ->site('https://maria.com.br')
        ->incomeValue(7500)
);

echo "Account created with ID: {$account->id}\n";
echo "API Key: {$account->apiKey}\n";
```

## Webhook Configuration

### Creating Account with Webhooks

You can configure webhooks during account creation to receive real-time notifications:

```php
use Leopaulo88\Asaas\Entities\Common\Webhook;
use Leopaulo88\Asaas\Enums\WebhookEvent;
use Leopaulo88\Asaas\Enums\WebhookSendType;

// Using array data
$accountData = [
    'name' => 'João Silva',
    'email' => 'joao@exemplo.com',
    'cpfCnpj' => '12345678901',
    'webhooks' => [
        [
            'name' => 'Payment Webhook',
            'url' => 'https://meusite.com/webhook/payment',
            'email' => 'admin@meusite.com',
            'enabled' => true,
            'apiVersion' => 3,
            'authToken' => 'meu_token_secreto_123',
            'sendType' => 'SEQUENTIALLY',
            'events' => [
                'PAYMENT_CREATED',
                'PAYMENT_CONFIRMED',
                'PAYMENT_RECEIVED',
                'PAYMENT_OVERDUE'
            ]
        ]
    ]
];

$account = Asaas::accounts()->create($accountData);
```

### Using Webhook Entity with Fluent Interface

```php
// Creating a payment webhook
$paymentWebhook = (new Webhook)
    ->name('Payment Notifications')
    ->url('https://meusite.com/webhook/payments')
    ->email('financeiro@meusite.com')
    ->enabled(true)
    ->apiVersion(3)
    ->authToken('webhook_token_payments_123')
    ->sendType(WebhookSendType::SEQUENTIALLY)
    ->events([
        WebhookEvent::PAYMENT_CREATED,
        WebhookEvent::PAYMENT_CONFIRMED,
        WebhookEvent::PAYMENT_RECEIVED,
        WebhookEvent::PAYMENT_OVERDUE,
        WebhookEvent::PAYMENT_REFUNDED
    ]);

// Creating a subscription webhook
$subscriptionWebhook = (new Webhook)
    ->name('Subscription Events')
    ->url('https://meusite.com/webhook/subscriptions')
    ->enabled(true)
    ->sendType(WebhookSendType::NON_SEQUENTIALLY)
    ->events([
        WebhookEvent::SUBSCRIPTION_CREATED,
        WebhookEvent::SUBSCRIPTION_UPDATED,
        WebhookEvent::SUBSCRIPTION_INACTIVATED
    ]);

// Creating account with multiple webhooks
$account = Asaas::accounts()->create(
    (new AccountCreate)
        ->name('Empresa ABC Ltda')
        ->email('contato@empresaabc.com')
        ->cpfCnpj('12.345.678/0001-90')
        ->companyType('LTDA')
        ->phone('1133334444')
        ->webhooks([$paymentWebhook, $subscriptionWebhook])
);
```

### Single Webhook Configuration

```php
// Adding a single webhook
$webhook = (new Webhook)
    ->name('All Events Webhook')
    ->url('https://meusite.com/webhook/all')
    ->email('admin@meusite.com')
    ->enabled(true)
    ->authToken('master_webhook_token')
    ->sendType(WebhookSendType::SEQUENTIALLY)
    ->events([
        WebhookEvent::PAYMENT_CREATED,
        WebhookEvent::PAYMENT_CONFIRMED,
        WebhookEvent::SUBSCRIPTION_CREATED,
        WebhookEvent::TRANSFER_CREATED
    ]);

$account = Asaas::accounts()->create(
    (new AccountCreate)
        ->name('Freelancer Silva')
        ->email('freelancer@exemplo.com')
        ->cpfCnpj('12345678901')
        ->webhooks($webhook) // Single webhook
);
```

### Available Webhook Events

The SDK supports all Asaas webhook events:

#### Payment Events
- `PAYMENT_CREATED` - Payment created
- `PAYMENT_AWAITING_RISK_ANALYSIS` - Payment awaiting risk analysis
- `PAYMENT_APPROVED_BY_RISK_ANALYSIS` - Payment approved by risk analysis
- `PAYMENT_REPROVED_BY_RISK_ANALYSIS` - Payment reproved by risk analysis
- `PAYMENT_AUTHORIZED` - Payment authorized
- `PAYMENT_UPDATED` - Payment updated
- `PAYMENT_CONFIRMED` - Payment confirmed
- `PAYMENT_RECEIVED` - Payment received
- `PAYMENT_CREDIT_CARD_CAPTURE_REFUSED` - Credit card capture refused
- `PAYMENT_ANTICIPATED` - Payment anticipated
- `PAYMENT_OVERDUE` - Payment overdue
- `PAYMENT_DELETED` - Payment deleted
- `PAYMENT_RESTORED` - Payment restored
- `PAYMENT_REFUNDED` - Payment refunded
- `PAYMENT_PARTIALLY_REFUNDED` - Payment partially refunded
- `PAYMENT_REFUND_IN_PROGRESS` - Payment refund in progress
- `PAYMENT_RECEIVED_IN_CASH_UNDONE` - Payment received in cash undone
- `PAYMENT_CHARGEBACK_REQUESTED` - Payment chargeback requested
- `PAYMENT_CHARGEBACK_DISPUTE` - Payment chargeback dispute
- `PAYMENT_AWAITING_CHARGEBACK_REVERSAL` - Payment awaiting chargeback reversal
- `PAYMENT_DUNNING_RECEIVED` - Payment dunning received
- `PAYMENT_DUNNING_REQUESTED` - Payment dunning requested
- `PAYMENT_BANK_SLIP_VIEWED` - Bank slip viewed
- `PAYMENT_CHECKOUT_VIEWED` - Checkout viewed

#### Subscription Events
- `SUBSCRIPTION_CREATED` - Subscription created
- `SUBSCRIPTION_UPDATED` - Subscription updated
- `SUBSCRIPTION_INACTIVATED` - Subscription inactivated
- `SUBSCRIPTION_DELETED` - Subscription deleted

#### Transfer Events
- `TRANSFER_CREATED` - Transfer created
- `TRANSFER_PENDING` - Transfer pending
- `TRANSFER_IN_BANK_PROCESSING` - Transfer in bank processing
- `TRANSFER_BLOCKED` - Transfer blocked

#### Invoice Events
- `INVOICE_CREATED` - Invoice created
- `INVOICE_UPDATED` - Invoice updated
- `INVOICE_AUTHORIZED` - Invoice authorized
- `INVOICE_CANCELED` - Invoice canceled

### Webhook Send Types

- `WebhookSendType::SEQUENTIALLY` - Events are sent one by one in order
- `WebhookSendType::NON_SEQUENTIALLY` - Events are sent as they occur, potentially out of order

## Entity Reference

### AccountResponse

Complete account information entity:

```php
public ?string $object;
public ?string $id;
public ?string $name;
public ?string $email;
public ?string $loginEmail;
public ?string $phone;
public ?string $mobilePhone;
public ?string $address;
public ?string $addressNumber;
public ?string $complement;
public ?string $province;
public ?string $postalCode;
public ?string $cpfCnpj;
public ?Carbon $birthDate;
public ?PersonType $personType;
public ?string $companyType;
public ?BrazilianState $city;
public ?BrazilianState $state;
public ?string $country;
public ?AccountNumber $accountNumber;
public ?string $site;
public ?bool $canReceiveTransfers;
```

### AccountNumber

Bank account information:

```php
public ?string $bank;
public ?string $agency;
public ?string $account;
public ?string $accountDigit;
```

## Error Handling

```php
use Leopaulo88\Asaas\Exceptions\{UnauthorizedException, AsaasException};

try {
    $account = Asaas::accounts()->info();
    
    // Process account information
    echo "Account loaded successfully: {$account->name}\n";
    
} catch (UnauthorizedException $e) {
    echo "Authentication failed: " . $e->getMessage() . "\n";
    // Handle invalid API key or expired token
    
} catch (AsaasException $e) {
    echo "API error: " . $e->getMessage() . "\n";
    // Handle other API errors
}
```

## Best Practices

### 1. Cache Account Information

Since account information doesn't change frequently, consider caching it:

```php
use Illuminate\Support\Facades\Cache;

class CachedAccountService
{
    public function getAccountInfo(): AccountResponse
    {
        return Cache::remember('asaas_account_info', 3600, function () {
            return Asaas::accounts()->info();
        });
    }
    
    public function refreshAccountCache(): AccountResponse
    {
        Cache::forget('asaas_account_info');
        return $this->getAccountInfo();
    }
}
```

### 2. Account Verification Check

```php
class AccountVerificationService
{
    public function checkAccountStatus(): array
    {
        $account = Asaas::accounts()->info();
        
        $status = [
            'is_verified' => false,
            'can_receive_transfers' => false,
            'missing_info' => []
        ];
        
        // Check if account has bank information
        if ($account->accountNumber) {
            $status['is_verified'] = true;
        } else {
            $status['missing_info'][] = 'Bank account information';
        }
        
        // Check transfer capability
        $status['can_receive_transfers'] = $account->canReceiveTransfers;
        
        // Check required fields
        if (!$account->cpfCnpj) {
            $status['missing_info'][] = 'CPF/CNPJ';
        }
        
        if (!$account->phone) {
            $status['missing_info'][] = 'Phone number';
        }
        
        if (!$account->address || !$account->postalCode) {
            $status['missing_info'][] = 'Complete address';
        }
        
        return $status;
    }
}
```

### 3. Account Information Display

```php
class AccountDisplayService
{
    public function formatAccountForDisplay(): array
    {
        $account = Asaas::accounts()->info();
        
        return [
            'basic_info' => [
                'name' => $account->name,
                'email' => $account->email,
                'phone' => $this->formatPhone($account->phone),
                'mobile_phone' => $this->formatPhone($account->mobilePhone),
            ],
            'address' => [
                'street' => $account->address,
                'number' => $account->addressNumber,
                'complement' => $account->complement,
                'district' => $account->province,
                'city' => $account->city,
                'state' => $account->state?->value,
                'postal_code' => $this->formatPostalCode($account->postalCode),
                'country' => $account->country
            ],
            'business_info' => [
                'person_type' => $account->personType->value,
                'document' => $this->formatDocument($account->cpfCnpj),
                'company_type' => $account->companyType,
                'birth_date' => $account->birthDate?->format('d/m/Y')
            ],
            'bank_info' => $account->accountNumber ? [
                'bank' => $account->accountNumber->bank,
                'agency' => $account->accountNumber->agency,
                'account' => $account->accountNumber->account,
                'account_digit' => $account->accountNumber->accountDigit
            ] : null,
            'capabilities' => [
                'can_receive_transfers' => $account->canReceiveTransfers
            ]
        ];
    }
    
    private function formatPhone(?string $phone): ?string
    {
        if (!$phone) return null;
        
        // Format Brazilian phone number
        $phone = preg_replace('/\D/', '', $phone);
        
        if (strlen($phone) === 11) {
            return sprintf('(%s) %s-%s',
                substr($phone, 0, 2),
                substr($phone, 2, 5),
                substr($phone, 7)
            );
        }
        
        return $phone;
    }
    
    private function formatPostalCode(?string $postalCode): ?string
    {
        if (!$postalCode) return null;
        
        $postalCode = preg_replace('/\D/', '', $postalCode);
        
        if (strlen($postalCode) === 8) {
            return sprintf('%s-%s',
                substr($postalCode, 0, 5),
                substr($postalCode, 5)
            );
        }
        
        return $postalCode;
    }
    
    private function formatDocument(?string $document): ?string
    {
        if (!$document) return null;
        
        $document = preg_replace('/\D/', '', $document);
        
        if (strlen($document) === 11) {
            // CPF format
            return sprintf('%s.%s.%s-%s',
                substr($document, 0, 3),
                substr($document, 3, 3),
                substr($document, 6, 3),
                substr($document, 9)
            );
        } elseif (strlen($document) === 14) {
            // CNPJ format
            return sprintf('%s.%s.%s/%s-%s',
                substr($document, 0, 2),
                substr($document, 2, 3),
                substr($document, 5, 3),
                substr($document, 8, 4),
                substr($document, 12)
            );
        }
        
        return $document;
    }
}
```

## Integration Examples

### Account Dashboard

```php
class DashboardController extends Controller
{
    public function index()
    {
        try {
            $accountService = new AccountDisplayService();
            $verificationService = new AccountVerificationService();
            
            $accountInfo = $accountService->formatAccountForDisplay();
            $verificationStatus = $verificationService->checkAccountStatus();
            
            return view('dashboard.account', [
                'account' => $accountInfo,
                'verification' => $verificationStatus
            ]);
            
        } catch (Exception $e) {
            return back()->withError('Failed to load account information');
        }
    }
}
```

### Account Health Check

```php
class AccountHealthService
{
    public function performHealthCheck(): array
    {
        $results = [
            'overall_status' => 'healthy',
            'checks' => []
        ];
        
        try {
            $account = Asaas::accounts()->info();
            
            // Check API connectivity
            $results['checks']['api_connectivity'] = [
                'status' => 'pass',
                'message' => 'API is accessible'
            ];
            
            // Check account verification
            if ($account->accountNumber) {
                $results['checks']['account_verification'] = [
                    'status' => 'pass',
                    'message' => 'Account is verified'
                ];
            } else {
                $results['checks']['account_verification'] = [
                    'status' => 'warn',
                    'message' => 'Account verification pending'
                ];
                $results['overall_status'] = 'warning';
            }
            
            // Check transfer capability
            if ($account->canReceiveTransfers) {
                $results['checks']['transfer_capability'] = [
                    'status' => 'pass',
                    'message' => 'Can receive transfers'
                ];
            } else {
                $results['checks']['transfer_capability'] = [
                    'status' => 'fail',
                    'message' => 'Cannot receive transfers'
                ];
                $results['overall_status'] = 'unhealthy';
            }
            
        } catch (UnauthorizedException $e) {
            $results['overall_status'] = 'critical';
            $results['checks']['authentication'] = [
                'status' => 'fail',
                'message' => 'Authentication failed: ' . $e->getMessage()
            ];
            
        } catch (Exception $e) {
            $results['overall_status'] = 'error';
            $results['checks']['general'] = [
                'status' => 'fail',
                'message' => 'API error: ' . $e->getMessage()
            ];
        }
        
        return $results;
    }
}
```
