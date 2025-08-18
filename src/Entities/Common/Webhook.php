<?php

namespace Leopaulo88\Asaas\Entities\Common;

use Leopaulo88\Asaas\Entities\BaseEntity;
use Leopaulo88\Asaas\Enums\RecurringFrequency;
use Leopaulo88\Asaas\Enums\WebhookEvent;
use Leopaulo88\Asaas\Enums\WebhookSendType;

class Webhook extends BaseEntity
{
    public function __construct(
        public ?string $name = null,
        public ?string $url = null,
        public ?string $email = null,
        public ?bool $enabled = null,
        public ?bool $interrupted = null,
        public ?int $apiVersion = null,
        public ?string $authToken = null,
        public ?WebhookSendType $sendType = null,
        /** @var WebhookEvent[] $events */
        public ?array $events = null,
    ) {}

    public function name(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function url(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function email(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function enabled(bool $enabled): self
    {
        $this->enabled = $enabled;

        return $this;
    }

    public function interrupted(bool $interrupted): self
    {
        $this->interrupted = $interrupted;

        return $this;
    }

    public function apiVersion(int $apiVersion): self
    {
        $this->apiVersion = $apiVersion;

        return $this;
    }

    public function authToken(string $authToken): self
    {
        $this->authToken = $authToken;

        return $this;
    }

    public function sendType(WebhookSendType $sendType): self
    {
        $this->sendType = $sendType;

        return $this;
    }

    /**
     * @param WebhookEvent[] $events
     * @return $this
     */
    public function events(array $events): self
    {
        $this->events = $events;

        return $this;
    }
}
