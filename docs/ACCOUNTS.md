# Account Resource

The Account Resource allows you to retrieve information about your Asaas account, including account details, balance, and configuration settings.

## Table of Contents

- [Getting Account Information](#getting-account-information)
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
