<?php

namespace Leopaulo88\Asaas\Entities\Responses;

use Leopaulo88\Asaas\Contracts\EntityFactoryInterface;
use Leopaulo88\Asaas\Http\ResponseHydrator;

abstract class BaseResponse implements EntityFactoryInterface
{
    public function __construct(array $attributes = [])
    {
        $hydrator = new ResponseHydrator;
        $validatedData = $hydrator->validateAndTransformData($attributes, static::class);

        foreach ($validatedData as $key => $value) {
            if (property_exists($this, $key)) {
                $this->{$key} = $value;
            }
        }
    }

    public function toArray(bool $preserveEmpty = false): array
    {
        $data = [];
        foreach (get_object_vars($this) as $key => $value) {
            if ($preserveEmpty || $value !== null) {
                $data[$key] = $value instanceof \BackedEnum ? $value->value : $value;
            }
        }

        return $data;
    }

    public static function fromArray(array $data): static
    {
        return new static($data);
    }

    public static function fromResponse(\Illuminate\Http\Client\Response $response): static
    {
        return new static($response->json() ?? []);
    }
}
