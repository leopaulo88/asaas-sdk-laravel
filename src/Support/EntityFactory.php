<?php

namespace Leopaulo88\Asaas\Support;

use Illuminate\Http\Client\Response;

class EntityFactory
{
    protected static array $entityMap = [
        'customer' => \Leopaulo88\Asaas\Entities\Customer\CustomerResponse::class,
        'account' => \Leopaulo88\Asaas\Entities\Account\AccountResponse::class,
        'list' => \Leopaulo88\Asaas\Entities\List\ListResponse::class,
        'payment' => \Leopaulo88\Asaas\Entities\Payment\PaymentResponse::class,
        'subscription' => \Leopaulo88\Asaas\Entities\Subscription\SubscriptionResponse::class,
    ];

    protected static array $conversionStack = [];

    public static function createFromResponse(Response $response)
    {
        $data = $response->json();

        if (! is_array($data)) {
            return $data;
        }

        return static::createFromArray($data);
    }

    public static function createFromArray(array $data)
    {
        $objectType = $data['object'] ?? null;

        if (! $objectType) {
            return $data;
        }

        if (! isset(static::$entityMap[$objectType])) {
            return $data;
        }

        $dataHash = md5(serialize($data));
        if (in_array($dataHash, static::$conversionStack)) {
            return $data;
        }

        static::$conversionStack[] = $dataHash;

        try {
            $entityClass = static::$entityMap[$objectType];
            $result = $entityClass::fromArray($data);
        } finally {
            array_pop(static::$conversionStack);
        }

        return $result;
    }

    public static function registerEntity(string $objectType, string $entityClass): void
    {
        static::$entityMap[$objectType] = $entityClass;
    }

    public static function getEntityMap(): array
    {
        return static::$entityMap;
    }

    public static function isRegistered(string $objectType): bool
    {
        return isset(static::$entityMap[$objectType]);
    }

    public static function getEntityClass(string $objectType): ?string
    {
        return static::$entityMap[$objectType] ?? null;
    }

    public static function unregisterEntity(string $objectType): void
    {
        unset(static::$entityMap[$objectType]);
    }

    public static function createWithFallback($data, ?string $fallbackClass = null)
    {
        if (is_array($data)) {
            $entity = static::createFromArray($data);

            if (is_array($entity) && $fallbackClass) {
                return $fallbackClass::fromArray($data);
            }

            return $entity;
        }

        return $data;
    }

    public static function createCollectionFromArray(array $data): array
    {
        return array_map(function ($item) {
            if (is_array($item)) {
                return static::createFromArray($item);
            }

            return $item;
        }, $data);
    }

    public static function createCollectionAs(array $data, string $entityClass): array
    {
        return array_map(function ($item) use ($entityClass) {
            if (is_array($item)) {
                return $entityClass::fromArray($item);
            }

            return $item;
        }, $data);
    }
}
