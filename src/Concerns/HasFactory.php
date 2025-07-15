<?php

namespace Leopaulo88\Asaas\Concerns;

use Leopaulo88\Asaas\Contracts\EntityFactoryInterface;

trait HasFactory
{
    /**
     * @implements EntityFactoryInterface
     */
    public static function fromArray(array $data): static
    {
        return new static($data);
    }

    public static function fromResponse(\Illuminate\Http\Client\Response $response): static
    {
        return new static($response->json() ?? []);
    }
}
