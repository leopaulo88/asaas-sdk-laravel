<?php

namespace Leopaulo88\Asaas\Contracts;

interface EntityInterface
{
    public static function fromArray(array $data): static;

    public function toArray(bool $preserveEmpty = false): array;
}
