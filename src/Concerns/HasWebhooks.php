<?php

namespace Leopaulo88\Asaas\Concerns;

use Leopaulo88\Asaas\Resources\WebhookResource;

trait HasWebhooks
{
    public function webhooks(): WebhookResource
    {
        return new WebhookResource($this->client);
    }
}
