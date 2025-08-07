<?php

namespace Leopaulo88\Asaas\Entities;

use Carbon\Carbon;
use Leopaulo88\Asaas\Contracts\EntityInterface;
use Leopaulo88\Asaas\Support\ObjectHydrator;

abstract class BaseEntity implements EntityInterface
{
    public static function make(): static
    {
        /** @var static */
        return new static;
    }

    public function toArray(bool $preserveEmpty = false): array
    {
        $data = [];

        foreach (get_object_vars($this) as $key => $value) {
            if ($preserveEmpty || $value !== null) {
                if (is_array($value)) {
                    foreach ($value as $v) {
                        if (is_object($v) && method_exists($v, 'toArray')) {
                            $data[$key][] = $v->toArray($preserveEmpty);
                        } else {
                            $data[$key][] = $v;
                        }
                    }
                } elseif ($value instanceof \BackedEnum) {
                    $data[$key] = $value->value;
                } elseif ($value instanceof \Carbon\Carbon) {
                    // Converter Carbon para formato apropriado
                    if ($this->isDateTimeField($key)) {
                        $data[$key] = $value->format('Y-m-d H:i:s');
                    } else {
                        $data[$key] = $value->format('Y-m-d');
                    }
                } elseif (is_object($value) && method_exists($value, 'toArray')) {
                    $data[$key] = $value->toArray();
                } else {
                    $data[$key] = $value;
                }
            }
        }

        return $data;
    }

    private function isDateTimeField(string $fieldName): bool
    {
        $dateTimeFields = [
            'dateCreated',
            'effectiveDate',
        ];

        return in_array($fieldName, $dateTimeFields);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): static
    {
        /** @var static */
        $instance = new static;
        $hydrator = new ObjectHydrator;
        $hydrator->fillObject($instance, $data);

        return $instance;
    }
}
