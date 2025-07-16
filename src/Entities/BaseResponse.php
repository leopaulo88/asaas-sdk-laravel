<?php

namespace Leopaulo88\Asaas\Entities;

use Leopaulo88\Asaas\Contracts\ResponseInterface;
use Leopaulo88\Asaas\Support\ObjectHydrator;

abstract class BaseResponse implements ResponseInterface
{
    public function __construct(array $attributes = [])
    {
        $hydrator = new ObjectHydrator;
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
