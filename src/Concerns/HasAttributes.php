<?php

namespace Leopaulo88\AsaasSdkLaravel\Concerns;

trait HasAttributes
{
    protected array $attributes = [];

    public function __construct(array $attributes = [])
    {
        $this->attributes = $attributes;
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return data_get($this->attributes, $key, $default);
    }

    public function has(string $key): bool
    {
        return data_get($this->attributes, $key) !== null;
    }

    public function toArray(): array
    {
        return $this->attributes;
    }

    public function setAttribute(string $key, mixed $value): self
    {
        $this->attributes[$key] = $value;
        return $this;
    }
}
