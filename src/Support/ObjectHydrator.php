<?php

namespace Leopaulo88\Asaas\Support;

use Carbon\Carbon;

class ObjectHydrator
{
    private static array $reflectionCache = [];

    private static array $propertyTypeCache = [];

    private static array $useStatementsCache = [];

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
            if (! isset(self::$reflectionCache[$className])) {
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

        $cacheKey = $targetClass.'::'.$key;

        if (! isset(self::$propertyTypeCache[$cacheKey])) {
            $this->cachePropertyType($targetClass, $key);
        }

        $propertyInfo = self::$propertyTypeCache[$cacheKey] ?? null;

        if (! $propertyInfo) {
            return $value;
        }

        $propertyType = $propertyInfo['type'];
        $isNullable = $propertyInfo['nullable'];
        $arrayElementType = $propertyInfo['arrayElementType'] ?? null;

        if ($propertyType === 'array') {
            if (! is_array($value)) {
                $value = [$value];
            }

            if ($arrayElementType && class_exists($arrayElementType)) {
                return array_map(function ($item) use ($arrayElementType) {
                    if (is_array($item)) {
                        return $this->createObjectInstance($arrayElementType, $item);
                    }

                    return $item;
                }, $value);
            }

            return $value;
        }

        if (in_array($propertyType, ['string', 'int', 'float', 'bool'])) {
            return $this->castBuiltInType($value, $propertyType);
        }

        // Handle Carbon date conversion
        if ($propertyType === 'Carbon\Carbon' && is_string($value)) {
            return $this->createCarbonInstance($value);
        }

        if (class_exists($propertyType)) {
            $objectInstance = $this->createObjectInstance($propertyType, $value);

            // If object creation failed and we have a nullable type, return null
            if ($objectInstance === $value && ! ($objectInstance instanceof $propertyType)) {
                return $isNullable ? null : $value;
            }

            return $objectInstance;
        }

        return $value;
    }

    private function castBuiltInType($value, string $type)
    {
        return match ($type) {
            'string' => (string) $value,
            'int' => (int) $value,
            'float' => (float) $value,
            'bool' => (bool) $value,
            default => $value
        };
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
            if (! isset(self::$reflectionCache[$className])) {
                self::$reflectionCache[$className] = new \ReflectionClass($className);
            }

            $reflection = self::$reflectionCache[$className];
            $property = $reflection->getProperty($key);
            $type = $property->getType();

            $cacheKey = $className.'::'.$key;

            $arrayElementType = null;
            $docComment = $property->getDocComment();
            if ($docComment) {
                if (preg_match('/@var\s+array<([^>]+)>/', $docComment, $matches)) {
                    $arrayElementType = trim($matches[1]);
                } elseif (preg_match('/@var\s+([^\\s\\[]+)\[\]/', $docComment, $matches)) {
                    $arrayElementType = trim($matches[1]);
                }

                if ($arrayElementType) {
                    $arrayElementType = $this->resolveClassName($arrayElementType, $reflection);
                }
            }

            if ($type instanceof \ReflectionNamedType) {
                self::$propertyTypeCache[$cacheKey] = [
                    'type' => $type->getName(),
                    'nullable' => $type->allowsNull(),
                    'arrayElementType' => $arrayElementType,
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
                    'nullable' => $nullable,
                    'arrayElementType' => $arrayElementType,
                ];
            } else {
                self::$propertyTypeCache[$cacheKey] = [
                    'type' => 'mixed',
                    'nullable' => true,
                    'arrayElementType' => $arrayElementType,
                ];
            }
        } catch (\ReflectionException) {
            $cacheKey = $className.'::'.$key;
            self::$propertyTypeCache[$cacheKey] = null;
        }
    }

    public static function clearCache(): void
    {
        self::$reflectionCache = [];
        self::$propertyTypeCache = [];
        self::$useStatementsCache = [];
    }

    public function getPropertyType(string $className, string $propertyName): ?array
    {
        $cacheKey = $className.'::'.$propertyName;

        if (! isset(self::$propertyTypeCache[$cacheKey])) {
            $this->cachePropertyType($className, $propertyName);
        }

        return self::$propertyTypeCache[$cacheKey] ?? null;
    }

    private function resolveClassName(string $className, \ReflectionClass $reflection): string
    {
        if (str_contains($className, '\\')) {
            return $className;
        }

        if (in_array($className, ['string', 'int', 'float', 'bool', 'array', 'object', 'mixed'])) {
            return $className;
        }

        $reflectionClassName = $reflection->getName();

        if (! isset(self::$useStatementsCache[$reflectionClassName])) {
            self::$useStatementsCache[$reflectionClassName] = $this->extractUseStatements($reflection);
        }

        $useStatements = self::$useStatementsCache[$reflectionClassName];

        if (isset($useStatements[$className])) {
            return $useStatements[$className];
        }

        $namespace = $reflection->getNamespaceName();
        if ($namespace) {
            $fullyQualifiedName = $namespace.'\\'.$className;
            if (class_exists($fullyQualifiedName)) {
                return $fullyQualifiedName;
            }
        }

        if (class_exists($className)) {
            return $className;
        }

        return $className;
    }

    private function extractUseStatements(\ReflectionClass $reflection): array
    {
        $useStatements = [];

        try {
            $file = $reflection->getFileName();
            if (! $file) {
                return [];
            }

            $content = file_get_contents($file);
            if (! $content) {
                return [];
            }

            preg_match_all('/^use\s+([^;]+);/m', $content, $matches);

            foreach ($matches[1] as $useStatement) {
                $useStatement = trim($useStatement);

                if (strpos($useStatement, ' as ') !== false) {
                    [$fullClassName, $alias] = explode(' as ', $useStatement, 2);
                    $useStatements[trim($alias)] = trim($fullClassName);
                } else {
                    $parts = explode('\\', $useStatement);
                    $className = end($parts);
                    $useStatements[$className] = $useStatement;
                }
            }
        } catch (\Throwable) {

        }

        return $useStatements;
    }

    private function createCarbonInstance(string $dateString): ?Carbon
    {
        try {

            if (preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/', $dateString)) {
                return Carbon::createFromFormat('Y-m-d H:i:s', $dateString);
            }

            if (preg_match('/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}/', $dateString)) {
                return Carbon::parse($dateString);
            }

            if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateString)) {
                return Carbon::createFromFormat('Y-m-d', $dateString)->startOfDay();
            }

            return Carbon::parse($dateString);
        } catch (\Throwable $e) {

            return null;
        }
    }
}
