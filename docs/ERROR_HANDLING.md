# Error Handling Guide

This guide covers comprehensive error handling strategies when using the Asaas SDK for Laravel, including specific exceptions, retry mechanisms, and best practices for production applications.

## Table of Contents

- [Exception Types](#exception-types)
- [Basic Error Handling](#basic-error-handling)
- [Advanced Error Handling](#advanced-error-handling)
- [Retry Mechanisms](#retry-mechanisms)
- [Logging and Monitoring](#logging-and-monitoring)
- [Production Best Practices](#production-best-practices)

## Exception Types

The SDK provides specific exception classes for different error scenarios:

### AsaasException (Base)
Base exception for all Asaas API errors.

```php
use Leopaulo88\Asaas\Exceptions\AsaasException;

try {
    $payment = Asaas::payments()->create($data);
} catch (AsaasException $e) {
    echo "API Error: " . $e->getMessage();
    echo "HTTP Status: " . $e->getCode();
    echo "Response Body: " . json_encode($e->getResponseBody());
}
```

### BadRequestException (400)
Validation errors and malformed requests.

```php
use Leopaulo88\Asaas\Exceptions\BadRequestException;

try {
    $customer = Asaas::customers()->create([
        'name' => '', // Invalid: empty name
        'email' => 'invalid-email', // Invalid: malformed email
        'cpfCnpj' => '123' // Invalid: incomplete CPF
    ]);
} catch (BadRequestException $e) {
    echo "Validation failed: " . $e->getMessage();
    
    // Get detailed field errors
    $errors = $e->getErrors();
    foreach ($errors as $field => $messages) {
        echo "Field '{$field}': " . implode(', ', $messages) . "\n";
    }
}
```

### UnauthorizedException (401)
Authentication failures.

```php
use Leopaulo88\Asaas\Exceptions\UnauthorizedException;

try {
    $customers = Asaas::customers()->list();
} catch (UnauthorizedException $e) {
    echo "Authentication failed: " . $e->getMessage();
    // Handle invalid API key, expired session, etc.
    
    // Log security event
    Log::warning('Asaas authentication failed', [
        'error' => $e->getMessage(),
        'timestamp' => now()
    ]);
}
```

### NotFoundException (404)
Resource not found errors.

```php
use Leopaulo88\Asaas\Exceptions\NotFoundException;

try {
    $customer = Asaas::customers()->find('cus_nonexistent');
} catch (NotFoundException $e) {
    echo "Customer not found: " . $e->getMessage();
    // Handle missing resources gracefully
}
```

### InvalidApiKeyException
Specific exception for API key issues.

```php
use Leopaulo88\Asaas\Exceptions\InvalidApiKeyException;

try {
    $account = Asaas::accounts()->info();
} catch (InvalidApiKeyException $e) {
    echo "Invalid API key: " . $e->getMessage();
    // Handle configuration issues
}
```

### InvalidEnvironmentException
Environment configuration errors.

```php
use Leopaulo88\Asaas\Exceptions\InvalidEnvironmentException;

try {
    $client = new AsaasClient('api_key', 'invalid_env');
} catch (InvalidEnvironmentException $e) {
    echo "Invalid environment: " . $e->getMessage();
    // Handle configuration errors
}
```

## Basic Error Handling

### Simple Try-Catch

```php
use Leopaulo88\Asaas\Exceptions\{
    AsaasException,
    BadRequestException,
    UnauthorizedException,
    NotFoundException
};

function createPayment(array $data): ?PaymentResponse
{
    try {
        return Asaas::payments()->create($data);
        
    } catch (BadRequestException $e) {
        // Handle validation errors
        Log::warning('Payment validation failed', [
            'data' => $data,
            'errors' => $e->getErrors()
        ]);
        return null;
        
    } catch (UnauthorizedException $e) {
        // Handle authentication errors
        Log::error('Asaas authentication failed', [
            'error' => $e->getMessage()
        ]);
        throw $e; // Re-throw for higher level handling
        
    } catch (NotFoundException $e) {
        // Handle not found errors
        Log::warning('Customer not found for payment', [
            'customer' => $data['customer'] ?? 'unknown'
        ]);
        return null;
        
    } catch (AsaasException $e) {
        // Handle other API errors
        Log::error('Asaas API error', [
            'message' => $e->getMessage(),
            'code' => $e->getCode(),
            'data' => $data
        ]);
        return null;
    }
}
```

### Error Response Helper

```php
class AsaasErrorHandler
{
    public static function handleException(AsaasException $e): array
    {
        $response = [
            'success' => false,
            'message' => $e->getMessage(),
            'code' => $e->getCode(),
            'type' => class_basename($e)
        ];
        
        if ($e instanceof BadRequestException) {
            $response['errors'] = $e->getErrors();
            $response['user_message'] = 'Please check your input data';
        } elseif ($e instanceof UnauthorizedException) {
            $response['user_message'] = 'Authentication failed';
        } elseif ($e instanceof NotFoundException) {
            $response['user_message'] = 'Requested resource not found';
        } else {
            $response['user_message'] = 'An unexpected error occurred';
        }
        
        return $response;
    }
}

// Usage
try {
    $payment = Asaas::payments()->create($data);
    return response()->json(['success' => true, 'payment' => $payment]);
} catch (AsaasException $e) {
    $errorResponse = AsaasErrorHandler::handleException($e);
    return response()->json($errorResponse, $e->getCode());
}
```

## Advanced Error Handling

### Service Layer with Error Handling

```php
class PaymentService
{
    private int $maxRetries = 3;
    private int $retryDelay = 1000; // milliseconds
    
    public function createPayment(array $data): PaymentResult
    {
        $attempt = 0;
        
        while ($attempt < $this->maxRetries) {
            try {
                $payment = Asaas::payments()->create($data);
                
                return new PaymentResult(
                    success: true,
                    payment: $payment
                );
                
            } catch (BadRequestException $e) {
                // Don't retry validation errors
                return new PaymentResult(
                    success: false,
                    error: $e,
                    userMessage: 'Invalid payment data: ' . $this->formatValidationErrors($e)
                );
                
            } catch (UnauthorizedException $e) {
                // Don't retry auth errors
                Log::error('Asaas authentication failed', [
                    'error' => $e->getMessage(),
                    'attempt' => $attempt + 1
                ]);
                
                return new PaymentResult(
                    success: false,
                    error: $e,
                    userMessage: 'Authentication failed'
                );
                
            } catch (AsaasException $e) {
                $attempt++;
                
                if ($attempt >= $this->maxRetries) {
                    Log::error('Payment creation failed after retries', [
                        'error' => $e->getMessage(),
                        'attempts' => $attempt,
                        'data' => $data
                    ]);
                    
                    return new PaymentResult(
                        success: false,
                        error: $e,
                        userMessage: 'Payment processing temporarily unavailable'
                    );
                }
                
                // Wait before retry
                usleep($this->retryDelay * 1000);
                Log::info('Retrying payment creation', [
                    'attempt' => $attempt,
                    'delay' => $this->retryDelay
                ]);
            }
        }
    }
    
    private function formatValidationErrors(BadRequestException $e): string
    {
        $errors = $e->getErrors();
        $messages = [];
        
        foreach ($errors as $field => $fieldErrors) {
            $messages[] = ucfirst($field) . ': ' . implode(', ', $fieldErrors);
        }
        
        return implode('; ', $messages);
    }
}

class PaymentResult
{
    public function __construct(
        public bool $success,
        public ?PaymentResponse $payment = null,
        public ?AsaasException $error = null,
        public ?string $userMessage = null
    ) {}
}
```

### Circuit Breaker Pattern

```php
use Illuminate\Support\Facades\Cache;

class AsaasCircuitBreaker
{
    private string $serviceName;
    private int $failureThreshold;
    private int $recoveryTimeout;
    private int $timeout;
    
    public function __construct(
        string $serviceName = 'asaas',
        int $failureThreshold = 5,
        int $recoveryTimeout = 60,
        int $timeout = 30
    ) {
        $this->serviceName = $serviceName;
        $this->failureThreshold = $failureThreshold;
        $this->recoveryTimeout = $recoveryTimeout;
        $this->timeout = $timeout;
    }
    
    public function call(callable $operation)
    {
        $state = $this->getState();
        
        if ($state === 'open') {
            throw new Exception('Circuit breaker is open. Service temporarily unavailable.');
        }
        
        try {
            $result = $operation();
            $this->onSuccess();
            return $result;
            
        } catch (AsaasException $e) {
            $this->onFailure();
            throw $e;
        }
    }
    
    private function getState(): string
    {
        $failures = Cache::get($this->getFailureKey(), 0);
        $lastFailure = Cache::get($this->getLastFailureKey());
        
        if ($failures >= $this->failureThreshold) {
            if ($lastFailure && (time() - $lastFailure) > $this->recoveryTimeout) {
                return 'half-open';
            }
            return 'open';
        }
        
        return 'closed';
    }
    
    private function onSuccess(): void
    {
        Cache::forget($this->getFailureKey());
        Cache::forget($this->getLastFailureKey());
    }
    
    private function onFailure(): void
    {
        $failures = Cache::get($this->getFailureKey(), 0) + 1;
        Cache::put($this->getFailureKey(), $failures, 300);
        Cache::put($this->getLastFailureKey(), time(), 300);
    }
    
    private function getFailureKey(): string
    {
        return "circuit_breaker:{$this->serviceName}:failures";
    }
    
    private function getLastFailureKey(): string
    {
        return "circuit_breaker:{$this->serviceName}:last_failure";
    }
}

// Usage
$circuitBreaker = new AsaasCircuitBreaker();

try {
    $payment = $circuitBreaker->call(function () use ($data) {
        return Asaas::payments()->create($data);
    });
} catch (Exception $e) {
    // Handle circuit breaker or API errors
    Log::error('Payment creation failed', ['error' => $e->getMessage()]);
}
```

## Retry Mechanisms

### Exponential Backoff

```php
class RetryableAsaasService
{
    public function executeWithRetry(callable $operation, int $maxAttempts = 3)
    {
        $attempt = 0;
        $baseDelay = 1000; // 1 second
        
        while ($attempt < $maxAttempts) {
            try {
                return $operation();
                
            } catch (BadRequestException | UnauthorizedException | NotFoundException $e) {
                // Don't retry these errors
                throw $e;
                
            } catch (AsaasException $e) {
                $attempt++;
                
                if ($attempt >= $maxAttempts) {
                    throw $e;
                }
                
                // Exponential backoff: 1s, 2s, 4s, 8s...
                $delay = $baseDelay * pow(2, $attempt - 1);
                usleep($delay * 1000);
                
                Log::info('Retrying Asaas operation', [
                    'attempt' => $attempt,
                    'delay_ms' => $delay,
                    'error' => $e->getMessage()
                ]);
            }
        }
    }
}

// Usage
$retryService = new RetryableAsaasService();

$payment = $retryService->executeWithRetry(function () use ($data) {
    return Asaas::payments()->create($data);
});
```

### Queue-Based Retry

```php
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreatePaymentJob implements ShouldQueue
{
    use Queueable, InteractsWithQueue, SerializesModels;
    
    public int $tries = 5;
    public int $backoff = 60; // seconds
    
    public function __construct(
        private array $paymentData,
        private int $userId
    ) {}
    
    public function handle(): void
    {
        try {
            $payment = Asaas::payments()->create($this->paymentData);
            
            // Notify user of success
            event(new PaymentCreated($payment, $this->userId));
            
        } catch (BadRequestException $e) {
            // Don't retry validation errors
            $this->fail($e);
            
        } catch (UnauthorizedException $e) {
            // Don't retry auth errors
            $this->fail($e);
            
        } catch (AsaasException $e) {
            // Let the queue retry other errors
            throw $e;
        }
    }
    
    public function failed(Throwable $exception): void
    {
        Log::error('Payment creation job failed permanently', [
            'user_id' => $this->userId,
            'payment_data' => $this->paymentData,
            'error' => $exception->getMessage()
        ]);
        
        // Notify user of failure
        event(new PaymentFailed($this->paymentData, $this->userId, $exception));
    }
}
```

## Logging and Monitoring

### Structured Logging

```php
class AsaasLogger
{
    public static function logApiCall(string $method, string $endpoint, array $data = [], ?AsaasException $error = null): void
    {
        $logData = [
            'method' => $method,
            'endpoint' => $endpoint,
            'timestamp' => now()->toISOString(),
            'data_size' => count($data)
        ];
        
        if ($error) {
            $logData['error'] = [
                'type' => class_basename($error),
                'message' => $error->getMessage(),
                'code' => $error->getCode()
            ];
            
            if ($error instanceof BadRequestException) {
                $logData['validation_errors'] = $error->getErrors();
            }
            
            Log::error('Asaas API call failed', $logData);
        } else {
            Log::info('Asaas API call successful', $logData);
        }
    }
}

// Usage in service
class MonitoredPaymentService
{
    public function createPayment(array $data): PaymentResponse
    {
        try {
            AsaasLogger::logApiCall('POST', '/payments', $data);
            $payment = Asaas::payments()->create($data);
            return $payment;
            
        } catch (AsaasException $e) {
            AsaasLogger::logApiCall('POST', '/payments', $data, $e);
            throw $e;
        }
    }
}
```

### Metrics Collection

```php
class AsaasMetrics
{
    public static function recordApiCall(string $endpoint, bool $success, int $responseTime, ?string $errorType = null): void
    {
        // Record metrics (using your preferred metrics system)
        $tags = [
            'endpoint' => $endpoint,
            'success' => $success ? 'true' : 'false'
        ];
        
        if ($errorType) {
            $tags['error_type'] = $errorType;
        }
        
        // Example using Laravel's built-in metrics (if available)
        // Metrics::increment('asaas.api_calls', $tags);
        // Metrics::histogram('asaas.response_time', $responseTime, $tags);
        
        // Or log for external processing
        Log::channel('metrics')->info('asaas_api_call', [
            'endpoint' => $endpoint,
            'success' => $success,
            'response_time_ms' => $responseTime,
            'error_type' => $errorType,
            'timestamp' => now()->timestamp
        ]);
    }
}
```

## Production Best Practices

### 1. Environment-Specific Error Handling

```php
class ProductionAsaasService
{
    public function __construct(
        private bool $isProduction = null
    ) {
        $this->isProduction = app()->environment('production');
    }
    
    public function createPayment(array $data): array
    {
        try {
            $payment = Asaas::payments()->create($data);
            
            return [
                'success' => true,
                'payment_id' => $payment->id,
                'status' => $payment->status->value
            ];
            
        } catch (BadRequestException $e) {
            $response = [
                'success' => false,
                'message' => 'Invalid payment data'
            ];
            
            // Include detailed errors only in non-production
            if (!$this->isProduction) {
                $response['errors'] = $e->getErrors();
                $response['debug_message'] = $e->getMessage();
            }
            
            return $response;
            
        } catch (AsaasException $e) {
            Log::error('Payment creation failed', [
                'error' => $e->getMessage(),
                'code' => $e->getCode(),
                'data' => $this->isProduction ? 'hidden' : $data
            ]);
            
            return [
                'success' => false,
                'message' => 'Payment processing temporarily unavailable'
            ];
        }
    }
}
```

### 2. Error Rate Monitoring

```php
class AsaasHealthMonitor
{
    public function checkApiHealth(): array
    {
        $metrics = [
            'status' => 'healthy',
            'checks' => []
        ];
        
        try {
            $start = microtime(true);
            Asaas::accounts()->info();
            $responseTime = (microtime(true) - $start) * 1000;
            
            $metrics['checks']['api_connectivity'] = [
                'status' => 'pass',
                'response_time_ms' => round($responseTime, 2)
            ];
            
            if ($responseTime > 5000) { // 5 seconds
                $metrics['status'] = 'degraded';
                $metrics['checks']['api_connectivity']['status'] = 'warn';
                $metrics['checks']['api_connectivity']['message'] = 'Slow response time';
            }
            
        } catch (UnauthorizedException $e) {
            $metrics['status'] = 'unhealthy';
            $metrics['checks']['authentication'] = [
                'status' => 'fail',
                'message' => 'Authentication failed'
            ];
            
        } catch (AsaasException $e) {
            $metrics['status'] = 'unhealthy';
            $metrics['checks']['api_connectivity'] = [
                'status' => 'fail',
                'message' => 'API unreachable',
                'error' => $e->getMessage()
            ];
        }
        
        return $metrics;
    }
}
```

### 3. Graceful Degradation

```php
class ResilientPaymentService
{
    public function processPayment(array $data): PaymentResult
    {
        try {
            // Try primary method
            $payment = Asaas::payments()->create($data);
            
            return PaymentResult::success($payment);
            
        } catch (AsaasException $e) {
            Log::warning('Primary payment method failed', [
                'error' => $e->getMessage()
            ]);
            
            // Fallback: queue for later processing
            CreatePaymentJob::dispatch($data, auth()->id())
                ->delay(now()->addMinutes(5));
            
            return PaymentResult::queued('Payment queued for processing');
        }
    }
}

class PaymentResult
{
    public function __construct(
        public string $status, // 'success', 'queued', 'failed'
        public ?PaymentResponse $payment = null,
        public ?string $message = null
    ) {}
    
    public static function success(PaymentResponse $payment): self
    {
        return new self('success', $payment);
    }
    
    public static function queued(string $message): self
    {
        return new self('queued', null, $message);
    }
    
    public static function failed(string $message): self
    {
        return new self('failed', null, $message);
    }
}
```

### 4. User-Friendly Error Messages

```php
class UserFriendlyErrorHandler
{
    private array $errorMessages = [
        BadRequestException::class => [
            'default' => 'Please check your information and try again.',
            'cpfCnpj' => 'Please enter a valid CPF or CNPJ.',
            'email' => 'Please enter a valid email address.',
            'value' => 'Please enter a valid payment amount.'
        ],
        UnauthorizedException::class => 'Authentication failed. Please contact support.',
        NotFoundException::class => 'The requested information was not found.',
        AsaasException::class => 'Service temporarily unavailable. Please try again later.'
    ];
    
    public function getUserMessage(AsaasException $e): string
    {
        $exceptionClass = get_class($e);
        
        if ($e instanceof BadRequestException) {
            return $this->getValidationMessage($e);
        }
        
        return $this->errorMessages[$exceptionClass] ?? $this->errorMessages[AsaasException::class];
    }
    
    private function getValidationMessage(BadRequestException $e): string
    {
        $errors = $e->getErrors();
        
        // Return specific message for known fields
        foreach (array_keys($errors) as $field) {
            if (isset($this->errorMessages[BadRequestException::class][$field])) {
                return $this->errorMessages[BadRequestException::class][$field];
            }
        }
        
        return $this->errorMessages[BadRequestException::class]['default'];
    }
}
```

This comprehensive error handling guide ensures your application can gracefully handle all types of errors when interacting with the Asaas API, providing a robust and user-friendly experience in production environments.
