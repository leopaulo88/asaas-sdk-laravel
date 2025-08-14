# Finance Resource

The Finance Resource allows you to retrieve financial information from your Asaas account, such as balance, payment statistics, and split statistics.

## Table of Contents
- [Get Balance](#get-balance)
- [Payment Statistics](#payment-statistics)
- [Split Statistics](#split-statistics)
- [Returned Entities](#returned-entities)
- [Best Practices](#best-practices)

## Get Balance

```php
use Leopaulo88\Asaas\Facades\Asaas;

$balance = Asaas::finance()->balance();
echo "Balance: {$balance->balance}";
```

## Payment Statistics

```php
$stats = Asaas::finance()->statistics(['foo' => 'bar']);
echo "Quantity: {$stats->quantity}";
echo "Total value: {$stats->value}";
echo "Net value: {$stats->netValue}";
```

## Split Statistics

```php
$splitStats = Asaas::finance()->splitStatistics();
echo "Income: {$splitStats->income}";
echo "Outcome: {$splitStats->outcome}";
```

## Returned Entities

- **BalanceResponse**: contains the account balance (`balance`).
- **StatisticResponse**: contains payment statistics (`quantity`, `value`, `netValue`).
- **SplitStatisticResponse**: contains split statistics (`income`, `outcome`).

## Best Practices
- Use available filters in `statistics()` to get more precise data.
- Always check your balance before performing financial operations.
