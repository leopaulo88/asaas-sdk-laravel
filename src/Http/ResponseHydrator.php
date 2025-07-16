<?php

namespace Leopaulo88\Asaas\Http;

use Leopaulo88\Asaas\Entities\Responses\BaseResponse;

class ResponseHydrator
{
    private static array $reflectionCache = [];

    private static array $propertyTypeCache = [];

    public function hydrate(array $data, string $responseClass): BaseResponse
    {
        $validatedData = $this->validateAndTransformData($data, $responseClass);

        return new $responseClass($validatedData);
    }

    public function validateAndTransformData(array $data, string $responseClass): array
    {
        $transformedData = [];

        foreach ($data as $key => $value) {
            if (! property_exists($responseClass, $key)) {
                continue;
            }

            $transformedData[$key] = $this->transformValue($key, $value, $responseClass);
        }

        return $transformedData;
    }

    private function transformValue(string $key, mixed $value, string $responseClass): mixed
    {
        if ($value === null) {
            return null;
        }

        $cacheKey = $responseClass.'::'.$key;

        if (! isset(self::$propertyTypeCache[$cacheKey])) {
            $this->cachePropertyType($responseClass, $key);
        }

        $propertyType = self::$propertyTypeCache[$cacheKey] ?? null;

        if ($propertyType && enum_exists($propertyType)) {
            return $propertyType::from($value);
        }

        return $value;
    }

    private function cachePropertyType(string $responseClass, string $key): void
    {
        try {
            if (! isset(self::$reflectionCache[$responseClass])) {
                self::$reflectionCache[$responseClass] = new \ReflectionClass($responseClass);
            }

            $reflection = self::$reflectionCache[$responseClass];
            $property = $reflection->getProperty($key);
            $type = $property->getType();

            $cacheKey = $responseClass.'::'.$key;

            if ($type instanceof \ReflectionNamedType) {
                self::$propertyTypeCache[$cacheKey] = $type->getName();
            } else {
                self::$propertyTypeCache[$cacheKey] = null;
            }
        } catch (\ReflectionException) {
            self::$propertyTypeCache[$responseClass.'::'.$key] = null;
        }
    }
}
