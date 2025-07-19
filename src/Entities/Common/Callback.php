<?php

namespace Leopaulo88\Asaas\Entities\Common;

use Leopaulo88\Asaas\Entities\BaseEntity;

class Callback extends BaseEntity
{
    public function __construct(
        public ?string $successUrl = null,
        public ?bool $autoRedirect = true
    ) {}

    public function successUrl(string $successUrl): self
    {
        $this->successUrl = $successUrl;

        return $this;
    }

    public function autoRedirect(bool $autoRedirect): self
    {
        $this->autoRedirect = $autoRedirect;

        return $this;
    }
}
