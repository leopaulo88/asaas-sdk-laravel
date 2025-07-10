<?php

namespace Hubooai\Asaas;

class AsaasFactory
{
    /**
     * Create a new Asaas instance with default configuration
     */
    public function make(?string $apiKey = null, ?string $environment = null): Asaas
    {
        return new Asaas($apiKey, $environment);
    }

    /**
     * Create a new Asaas instance with production environment
     */
    public function production(string $apiKey): Asaas
    {
        return new Asaas($apiKey, 'production');
    }

    /**
     * Create a new Asaas instance with sandbox environment
     */
    public function sandbox(string $apiKey): Asaas
    {
        return new Asaas($apiKey, 'sandbox');
    }

    /**
     * Create a new Asaas instance from existing configuration
     */
    public function fromConfig(array $config): Asaas
    {
        return new Asaas(
            $config['api_key'] ?? null,
            $config['environment'] ?? null
        );
    }
}
