# Customer Resource

The Customer Resource allows you to manage customers in your Asaas account. You can create, list, update, delete, and restore customers.

## Table of Contents

- [Creating Customers](#creating-customers)
- [Listing Customers](#listing-customers)
- [Finding a Customer](#finding-a-customer)
- [Updating Customers](#updating-customers)
- [Deleting Customers](#deleting-customers)
- [Restoring Customers](#restoring-customers)
- [Entity Reference](#entity-reference)

## Creating Customers

### Basic Customer Creation

```php
use Leopaulo88\Asaas\Facades\Asaas;

// Using array
$customer = Asaas::customers()->create([
    'name' => 'John Doe',
    'email' => 'john@example.com',
    'cpfCnpj' => '12345678901',
    'phone' => '11999999999'
]);
```

### Using Entity

```php
use Leopaulo88\Asaas\Entities\Customer\CustomerCreateEntity;

// Using constructor
$customerData = new CustomerCreateEntity(
    name: 'John Doe',
    email: 'john@example.com',
    cpfCnpj: '12345678901'
);

// Using fluent interface
$customerData = CustomerCreateEntity::make()
    ->name('John Doe')
    ->email('john@example.com')
    ->cpfCnpj('12345678901')
    ->phone('11999999999')
    ->address('123 Main Street')
    ->addressNumber('123')
    ->province('Downtown');

$customer = Asaas::customers()->create($customerData);
```

### Complete Customer with Address

```php
$customer = Asaas::customers()->create([
    'name' => 'Jane Smith',
    'email' => 'jane@example.com',
    'cpfCnpj' => '98765432100',
    'phone' => '11888888888',
    'mobilePhone' => '11777777777',
    'address' => '456 Oak Avenue',
    'addressNumber' => '456',
    'complement' => 'Apt 2B',
    'province' => 'Centro',
    'postalCode' => '12345678',
    'externalReference' => 'customer_123',
    'observations' => 'VIP Customer',
    'personType' => 'FISICA'
]);
```

## Listing Customers

### Basic List

```php
$customers = Asaas::customers()->list();

echo "Total customers: " . $customers->getTotalCount();
echo "Has more: " . ($customers->hasMore() ? 'Yes' : 'No');

foreach ($customers->getData() as $customer) {
    echo "Customer: {$customer->name} - {$customer->email}\n";
}
```

### With Filters

```php
$customers = Asaas::customers()->list([
    'name' => 'John',
    'email' => 'john@example.com',
    'cpfCnpj' => '12345678901',
    'groupName' => 'Premium',
    'externalReference' => 'customer_123',
    'offset' => 0,
    'limit' => 50
]);
```

### Pagination

```php
$page = 1;
$limit = 20;

do {
    $offset = ($page - 1) * $limit;
    
    $customers = Asaas::customers()->list([
        'offset' => $offset,
        'limit' => $limit
    ]);
    
    foreach ($customers->getData() as $customer) {
        // Process each customer
        echo "Processing: {$customer->name}\n";
    }
    
    $page++;
} while ($customers->hasMore());
```

## Finding a Customer

```php
$customer = Asaas::customers()->find('cus_123456789');

echo "Customer: {$customer->name}\n";
echo "Email: {$customer->email}\n";
echo "CPF/CNPJ: {$customer->cpfCnpj}\n";
echo "Created: {$customer->dateCreated->format('Y-m-d H:i:s')}\n";
```

## Updating Customers

### Using Array

```php
$customer = Asaas::customers()->update('cus_123456789', [
    'name' => 'John Updated',
    'email' => 'john.updated@example.com',
    'phone' => '11999999999'
]);
```

### Using Entity

```php
use Leopaulo88\Asaas\Entities\Customer\CustomerUpdateEntity;

$updateData = CustomerUpdateEntity::make()
    ->name('John Updated')
    ->email('john.updated@example.com')
    ->phone('11999999999')
    ->observations('Updated customer information');

$customer = Asaas::customers()->update('cus_123456789', $updateData);
```

## Deleting Customers

```php
$result = Asaas::customers()->delete('cus_123456789');

if ($result->deleted) {
    echo "Customer {$result->id} was successfully deleted\n";
}
```

## Restoring Customers

```php
$customer = Asaas::customers()->restore('cus_123456789');

echo "Customer restored: {$customer->name}\n";
echo "Deleted status: " . ($customer->deleted ? 'Yes' : 'No') . "\n";
```

## Entity Reference

### CustomerCreateEntity

Properties available for customer creation:

```php
public ?string $name = null;
public ?string $email = null;
public ?string $cpfCnpj = null;
public ?string $phone = null;
public ?string $mobilePhone = null;
public ?string $address = null;
public ?string $addressNumber = null;
public ?string $complement = null;
public ?string $province = null;
public ?string $postalCode = null;
public ?string $externalReference = null;
public ?bool $notificationDisabled = null;
public ?string $additionalEmails = null;
public ?string $municipalInscription = null;
public ?string $stateInscription = null;
public ?string $observations = null;
public ?string $groupName = null;
public ?string $company = null;
public ?PersonType $personType = null;
```

### CustomerUpdateEntity

Same properties as CustomerCreateEntity, used for updates.

### CustomerResponse

Response entity with all customer data:

```php
public ?string $object;
public ?string $id;
public ?Carbon $dateCreated;
public ?string $name;
public ?string $email;
public ?string $phone;
public ?string $mobilePhone;
public ?string $address;
public ?string $addressNumber;
public ?string $complement;
public ?string $province;
public ?string $postalCode;
public ?string $cpfCnpj;
public ?PersonType $personType;
public ?bool $deleted;
public ?string $additionalEmails;
public ?string $externalReference;
public ?bool $notificationDisabled;
public ?string $observations;
public ?string $municipalInscription;
public ?string $stateInscription;
public ?BrazilianState $city;
public ?BrazilianState $state;
public ?string $country;
```

## Error Handling

```php
use Leopaulo88\Asaas\Exceptions\{BadRequestException, NotFoundException};

try {
    $customer = Asaas::customers()->create([
        'name' => 'John Doe',
        // Missing required fields
    ]);
} catch (BadRequestException $e) {
    echo "Validation error: " . $e->getMessage() . "\n";
    $errors = $e->getErrors();
    foreach ($errors as $field => $messages) {
        echo "Field $field: " . implode(', ', $messages) . "\n";
    }
} catch (NotFoundException $e) {
    echo "Customer not found: " . $e->getMessage() . "\n";
}
```

## Examples

### Creating a Company Customer

```php
$company = Asaas::customers()->create([
    'name' => 'ACME Corporation',
    'email' => 'contact@acme.com',
    'cpfCnpj' => '12345678000195', // CNPJ
    'personType' => 'JURIDICA',
    'phone' => '1133334444',
    'address' => 'Corporate Avenue',
    'addressNumber' => '1000',
    'postalCode' => '01234567',
    'company' => 'ACME Corp',
    'stateInscription' => '123456789',
    'municipalInscription' => '987654321'
]);
```

### Using Different API Keys

```php
// Use different API key for specific tenant
$customer = Asaas::withApiKey($tenant->asaas_api_key)
    ->customers()
    ->create([
        'name' => 'Tenant Customer',
        'email' => 'customer@tenant.com',
        'cpfCnpj' => '12345678901'
    ]);

// Use production API key for specific operation
$customer = Asaas::withApiKey('production_key', 'production')
    ->customers()
    ->find('cus_123456789');
```

### Bulk Customer Processing

```php
$customerData = [
    ['name' => 'Customer 1', 'email' => 'customer1@example.com', 'cpfCnpj' => '11111111111'],
    ['name' => 'Customer 2', 'email' => 'customer2@example.com', 'cpfCnpj' => '22222222222'],
    // ... more customers
];

foreach ($customerData as $data) {
    try {
        $customer = Asaas::customers()->create($data);
        echo "Created customer: {$customer->id}\n";
    } catch (Exception $e) {
        echo "Error creating customer {$data['name']}: {$e->getMessage()}\n";
    }
}
```
