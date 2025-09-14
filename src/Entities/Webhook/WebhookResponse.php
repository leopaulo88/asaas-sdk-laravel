<?php

namespace Leopaulo88\Asaas\Entities\Webhook;

use Leopaulo88\Asaas\Entities\BaseResponse;

class WebhookResponse extends BaseResponse
{
    public ?string $id = null;

    public ?string $name = null;

    public ?string $url = null;

    public ?string $email = null;

    public ?bool $enabled = null;

    public ?bool $interrupted = null;

    public ?int $apiVersion = null;

    public ?bool $hasAuthToken = null;

    public ?string $sendType = null;

    public ?array $events = null;
}
