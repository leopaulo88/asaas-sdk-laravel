<?php

namespace Leopaulo88\Asaas\Resources;

use Leopaulo88\Asaas\Entities\Account\AccessTokenCreate;
use Leopaulo88\Asaas\Entities\Account\AccessTokenResponse;
use Leopaulo88\Asaas\Entities\Account\AccessTokenUpdate;
use Leopaulo88\Asaas\Entities\List\ListResponse;
use Leopaulo88\Asaas\Support\AsaasClient;

class AccessTokensResource extends BaseResource
{
    protected string $accountId;

    public function __construct(AsaasClient $client, string $accountId)
    {
        parent::__construct($client);
        $this->accountId = $accountId;
    }

    public function list(): ListResponse
    {
        return $this->get("/accounts/{$this->accountId}/accessTokens");
    }

    public function create(array|AccessTokenCreate $data): AccessTokenResponse
    {
        if (is_array($data)) {
            $data = AccessTokenCreate::fromArray($data);
        }

        $res = $this->post("/accounts/{$this->accountId}/accessTokens", $data->toArray());

        return AccessTokenResponse::fromArray($res);
    }

    public function update(string $id, array|AccessTokenUpdate $data): AccessTokenResponse
    {
        if (is_array($data)) {
            $data = AccessTokenUpdate::fromArray($data);
        }

        $res = $this->put("/accounts/{$this->accountId}/accessTokens/{$id}", $data->toArray());

        return AccessTokenResponse::fromArray($res);
    }

    public function remove(string $id): mixed
    {
        return $this->delete("/accounts/{$this->accountId}/accessTokens/{$id}");
    }
}
