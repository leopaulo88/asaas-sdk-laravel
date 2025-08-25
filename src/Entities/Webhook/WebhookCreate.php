<?php

namespace Leopaulo88\Asaas\Entities\Webhook;

use Leopaulo88\Asaas\Entities\BaseEntity;

class WebhookCreate extends BaseEntity
{
    public function __construct(
        public ?string $name = null,
        public ?string $url = null,
        public ?string $email = null,
        public ?bool $enabled = null,
        public ?bool $interrupted = null,
        public ?int $apiVersion = null,
        public ?string $authToken = null,
        public ?string $sendType = null,
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

    public function sendType(string $sendType): self
    {
        $this->sendType = $sendType;

        return $this;
    }

    public function events(array $events): self
    {
        $this->events = $events;

        return $this;
    }
}
