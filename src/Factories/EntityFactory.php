<?php

namespace Leopaulo88\Asaas\Factories;

use Illuminate\Http\Client\Response;

class EntityFactory
{
    /**
     * Mapping of object types to their corresponding entity classes
     */
    protected static array $entityMap = [
        'customer' => \Leopaulo88\Asaas\Entities\Responses\CustomerResponse::class,
        'payment' => \Leopaulo88\Asaas\Entities\Responses\PaymentResponse::class,
        'list' => \Leopaulo88\Asaas\Entities\Responses\ListResponse::class,
        // Add more as they are created
    ];

    /**
     * Stack to prevent infinite recursion
     */
    protected static array $conversionStack = [];

    /**
     * Create an entity from API response automatically based on object type
     */
    public static function createFromResponse(Response $response)
    {
        $data = $response->json();

        if (! is_array($data)) {
            return $data;
        }

        return static::createFromArray($data);
    }

    /**
     * Create an entity from array data automatically based on object type
     */
    public static function createFromArray(array $data)
    {
        $objectType = $data['object'] ?? null;

        if (! $objectType) {
            return $data; // Retorna array original se não tiver 'object'
        }

        if (! isset(static::$entityMap[$objectType])) {
            return $data; // Retorna array original se não tiver mapeamento
        }

        // Proteção contra recursão infinita
        $dataHash = md5(serialize($data));
        if (in_array($dataHash, static::$conversionStack)) {
            return $data; // Retorna array se já está sendo processado
        }

        // Adiciona à pilha de conversão
        static::$conversionStack[] = $dataHash;

        try {
            $entityClass = static::$entityMap[$objectType];
            $result = $entityClass::fromArray($data);
        } finally {
            // Remove da pilha sempre, mesmo se der erro
            array_pop(static::$conversionStack);
        }

        return $result;
    }

    /**
     * Register a new entity mapping
     */
    public static function registerEntity(string $objectType, string $entityClass): void
    {
        static::$entityMap[$objectType] = $entityClass;
    }

    /**
     * Get all registered entity mappings
     */
    public static function getEntityMap(): array
    {
        return static::$entityMap;
    }

    /**
     * Check if an object type is registered
     */
    public static function isRegistered(string $objectType): bool
    {
        return isset(static::$entityMap[$objectType]);
    }

    /**
     * Get the entity class for an object type
     */
    public static function getEntityClass(string $objectType): ?string
    {
        return static::$entityMap[$objectType] ?? null;
    }

    /**
     * Unregister an entity mapping
     */
    public static function unregisterEntity(string $objectType): void
    {
        unset(static::$entityMap[$objectType]);
    }

    /**
     * Create entity with fallback to array if mapping doesn't exist
     */
    public static function createWithFallback($data, ?string $fallbackClass = null)
    {
        if (is_array($data)) {
            $entity = static::createFromArray($data);

            // Se retornou array e temos fallback, use o fallback
            if (is_array($entity) && $fallbackClass) {
                return $fallbackClass::fromArray($data);
            }

            return $entity;
        }

        return $data;
    }

    /**
     * Create a collection of entities from array data
     */
    public static function createCollectionFromArray(array $data): array
    {
        return array_map(function ($item) {
            return static::createFromArray($item);
        }, $data);
    }

    /**
     * Create a collection of specific entity instances
     *
     * @param  string  $entityClass  The entity class to map data to
     */
    public static function createCollectionAs(array $data, string $entityClass): array
    {
        return array_map(function ($item) use ($entityClass) {
            return new $entityClass($item);
        }, $data);
    }
}
