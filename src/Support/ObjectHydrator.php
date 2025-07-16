<?php

namespace Leopaulo88\Asaas\Support;

class ObjectHydrator
{
    private static array $reflectionCache = [];
    private static array $propertyTypeCache = [];

    public function validateAndTransformData(array $data, string $targetClass): array
    {
        $validatedData = [];

        foreach ($data as $key => $value) {
            if ($this->hasProperty($targetClass, $key)) {
                $validatedData[$key] = $this->transformValue($value, $targetClass, $key);
            }
        }

        return $validatedData;
    }

    public function fillObject(object &$instance, array $data): void
    {
        $targetClass = get_class($instance);

        foreach ($data as $key => $value) {
            if ($this->hasProperty($targetClass, $key)) {
                $transformedValue = $this->transformValue($value, $targetClass, $key);

                if (property_exists($instance, $key)) {
                    $instance->{$key} = $transformedValue;
                }
            }
        }
    }

    private function hasProperty(string $className, string $propertyName): bool
    {
        try {
            if (!isset(self::$reflectionCache[$className])) {
                self::$reflectionCache[$className] = new \ReflectionClass($className);
            }

            return self::$reflectionCache[$className]->hasProperty($propertyName);
        } catch (\ReflectionException) {
            return false;
        }
    }

    private function transformValue($value, string $targetClass, string $key)
    {
        if ($value === null) {
            return null;
        }

        $cacheKey = $targetClass . '::' . $key;

        if (!isset(self::$propertyTypeCache[$cacheKey])) {
            $this->cachePropertyType($targetClass, $key);
        }

        $propertyInfo = self::$propertyTypeCache[$cacheKey] ?? null;

        if (!$propertyInfo) {
            return $value;
        }

        $propertyType = $propertyInfo['type'];
        $isNullable = $propertyInfo['nullable'];

        if ($value === null && $isNullable) {
            return null;
        }

        if ($propertyType === 'array') {
            return is_array($value) ? $value : [$value];
        }

        if (in_array($propertyType, ['string', 'int', 'float', 'bool'])) {
            return $this->castBuiltInType($value, $propertyType);
        }

        if (enum_exists($propertyType)) {
            return $this->createEnumInstance($propertyType, $value);
        }

        if (class_exists($propertyType)) {
            $objectInstance = $this->createObjectInstance($propertyType, $value);


            if ($objectInstance === $value && !($objectInstance instanceof $propertyType)) {

                if ($isNullable) {
                    return null;
                }
                return $value;
            }

            return $objectInstance;
        }

        return $value;
    }

    private function castBuiltInType($value, string $type)
    {
        return match ($type) {
            'string' => (string)$value,
            'int' => (int)$value,
            'float' => (float)$value,
            'bool' => (bool)$value,
            default => $value
        };
    }

    private function createEnumInstance(string $enumClass, $value)
    {
        try {
            if (method_exists($enumClass, 'from')) {
                return $enumClass::from($value);
            }

            if (method_exists($enumClass, 'tryFrom')) {
                return $enumClass::tryFrom($value) ?? $value;
            }

            return $value;
        } catch (\Throwable) {
            return $value;
        }
    }

    private function createObjectInstance(string $className, $value)
    {
        try {
            if (is_object($value) && $value instanceof $className) {
                return $value;
            }

            if (is_array($value) && method_exists($className, 'fromArray')) {
                return $className::fromArray($value);
            }

            if (is_array($value)) {
                return new $className(...array_values($value));
            } else {
                return new $className($value);
            }
        } catch (\Throwable) {
            return $value;
        }
    }

    private function cachePropertyType(string $className, string $key): void
    {
        try {
            if (!isset(self::$reflectionCache[$className])) {
                self::$reflectionCache[$className] = new \ReflectionClass($className);
            }

            $reflection = self::$reflectionCache[$className];
            $property = $reflection->getProperty($key);
            $type = $property->getType();

            $cacheKey = $className . '::' . $key;

            if ($type instanceof \ReflectionNamedType) {
                self::$propertyTypeCache[$cacheKey] = [
                    'type' => $type->getName(),
                    'nullable' => $type->allowsNull()
                ];
            } elseif ($type instanceof \ReflectionUnionType) {
                $types = [];
                $nullable = false;

                foreach ($type->getTypes() as $unionType) {
                    if ($unionType->getName() === 'null') {
                        $nullable = true;
                    } else {
                        $types[] = $unionType->getName();
                    }
                }

                self::$propertyTypeCache[$cacheKey] = [
                    'type' => $types[0] ?? 'mixed',
                    'nullable' => $nullable
                ];
            } else {
                self::$propertyTypeCache[$cacheKey] = [
                    'type' => 'mixed',
                    'nullable' => true
                ];
            }
        } catch (\ReflectionException) {
            $cacheKey = $className . '::' . $key;
            self::$propertyTypeCache[$cacheKey] = null;
        }
    }


    public static function clearCache(): void
    {
        self::$reflectionCache = [];
        self::$propertyTypeCache = [];
    }


    public function getPropertyType(string $className, string $propertyName): ?array
    {
        $cacheKey = $className . '::' . $propertyName;

        if (!isset(self::$propertyTypeCache[$cacheKey])) {
            $this->cachePropertyType($className, $propertyName);
        }

        return self::$propertyTypeCache[$cacheKey] ?? null;
    }
}
