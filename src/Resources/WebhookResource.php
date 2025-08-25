<?php

namespace Leopaulo88\Asaas\Resources;

use Leopaulo88\Asaas\Entities\Common\Deleted;
use Leopaulo88\Asaas\Entities\List\ListResponse;
use Leopaulo88\Asaas\Entities\Webhook\WebhookCreate;
use Leopaulo88\Asaas\Entities\Webhook\WebhookResponse;
use Leopaulo88\Asaas\Entities\Webhook\WebhookUpdate;

class WebhookResource extends BaseResource
{
    protected string $endpoint = 'webhooks';

    /**
     * Create a new webhook
     */
    public function create(array|WebhookCreate $create): WebhookResponse
    {
        if (is_array($create)) {
            $create = WebhookCreate::fromArray($create);
        }

        $res = $this->post($this->endpoint, $create->toArray());

        return WebhookResponse::fromArray($res);
    }

    /**
     * List webhooks with optional filters
     */
    public function list(array $filters = []): ListResponse
    {
        $response = $this->get($this->endpoint, $filters);

        return new ListResponse($response);
    }

    /**
     * Find a specific webhook by ID
     */
    public function find(string $webhookId): WebhookResponse
    {
        $res = $this->get("{$this->endpoint}/{$webhookId}");

        return WebhookResponse::fromArray($res);
    }

    /**
     * Update an existing webhook
     */
    public function update(string $webhookId, array|WebhookUpdate $update): object
    {
        if (is_array($update)) {
            $update = WebhookUpdate::fromArray($update);
        }

        $res = $this->put("{$this->endpoint}/{$webhookId}", $update->toArray());

        return WebhookResponse::fromArray($res);
    }

    /**
     * Remove a webhook
     */
    public function remove(string $webhookId): Deleted
    {
        $res = $this->delete("{$this->endpoint}/{$webhookId}");

        return Deleted::fromArray($res);
    }
}
