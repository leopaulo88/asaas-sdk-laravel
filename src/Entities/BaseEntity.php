<?php

namespace Leopaulo88\Asaas\Entities;

use Illuminate\Contracts\Support\ValidatedData;
use Leopaulo88\Asaas\Contracts\EntityInterface;
use Leopaulo88\Asaas\Http\ResponseHydrator;
use Leopaulo88\Asaas\Support\ObjectHydrator;

abstract class BaseEntity implements EntityInterface
{
    public static function make(): static
    {
        return new static();
    }

     public function toArray(bool $preserveEmpty = false): array
    {
        if (class_uses($this, ValidatedData::class)) {
            $this->validate();
        }

        $data = [];

        foreach (get_object_vars($this) as $key => $value) {
            if ($preserveEmpty || $value !== null) {
                if ($value instanceof \BackedEnum) {
                    $data[$key] = $value->value;
                } elseif (is_object($value) && method_exists($value, 'toArray')) {
                    $data[$key] = $value->toArray();
                } else {
                    $data[$key] = $value;
                }
            }
        }

        return $data;
    }

    public static function fromArray(array $data): static
    {
        $instance = new static();
        $hydrator = new ObjectHydrator();
        $hydrator->fillObject($instance, $data);
        return $instance;
    }
}
