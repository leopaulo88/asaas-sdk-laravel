<?php

namespace Leopaulo88\Asaas\Support;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;

class EntityFactory
{
    protected static array $conversionStack = [];

    protected static function getEntityMap(): array
    {
        return Config::get('asaas.entity_mapping', []);
    }

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

        $entityMap = static::getEntityMap();

        if (! isset($entityMap[$objectType])) {
            return $data;
        }

        $dataHash = md5(serialize($data));
        if (in_array($dataHash, static::$conversionStack)) {
            return $data;
        }

        static::$conversionStack[] = $dataHash;

        try {
            $entityClass = $entityMap[$objectType];
            $result = $entityClass::fromArray($data);
        } finally {
            array_pop(static::$conversionStack);
        }

        return $result;
    }

    public static function registerEntity(string $objectType, string $entityClass): void
    {
        $currentMapping = static::getEntityMapping();
        $currentMapping[$objectType] = $entityClass;

        Config::set('asaas.entity_mapping', $currentMapping);
    }

    public static function getEntityMapping(): array
    {
        return Config::get('asaas.entity_mapping', []);
    }

    public static function isRegistered(string $objectType): bool
    {
        $entityMap = static::getEntityMapping();

        return isset($entityMap[$objectType]);
    }

    public static function getEntityClass(string $objectType): ?string
    {
        $entityMap = static::getEntityMapping();

        return $entityMap[$objectType] ?? null;
    }

    public static function unregisterEntity(string $objectType): void
    {
        $currentMapping = Config::get('asaas.entity_mapping', []);
        unset($currentMapping[$objectType]);
        Config::set('asaas.entity_mapping', $currentMapping);
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

    public static function createCollectionFromArray(array $data): Collection
    {
        return collect(array_map(function ($item) {
            if (is_array($item)) {
                return static::createFromArray($item);
            }

            return $item;
        }, $data));
    }

    public static function createCollectionAs(array $data, string $entityClass): Collection
    {
        return collect(array_map(function ($item) use ($entityClass) {
            if (is_array($item)) {
                return $entityClass::fromArray($item);
            }

            return $item;
        }, $data));
    }
}
