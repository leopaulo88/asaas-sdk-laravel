<?php

namespace Leopaulo88\Asaas\Entities\Webhook;

use Leopaulo88\Asaas\Entities\BaseResponse;

class WebhookResponse extends BaseResponse
{
    public ?string $id;

    public ?string $name;

    public ?string $url;

    public ?string $email;

    public ?bool $enabled;

    public ?bool $interrupted;

    public ?int $apiVersion;

    public ?bool $hasAuthToken;

    public ?string $sendType;

    public ?array $events;
}
