<?php

namespace Leopaulo88\Asaas\Resources;

use Leopaulo88\Asaas\Entities\List\ListResponse;
use Leopaulo88\Asaas\Entities\Transfer\TransferCreate;
use Leopaulo88\Asaas\Entities\Transfer\TransferResponse;

class TransferResource extends BaseResource
{
    /**
     * Transfer to another Institution’s account or Pix key
     *
     * @see https://docs.asaas.com/reference/transfer-to-another-institution-account-or-pix-key
     *
     * Transfer to Asaas account
     * @see https://docs.asaas.com/reference/transfer-to-asaas-account-1
     */
    public function create(array|TransferCreate $create): TransferResponse
    {
        if (is_array($create)) {
            $create = TransferCreate::fromArray($create);
        }

        return $this->post('transfers', $create->toArray());
    }

    /**
     * List transfers
     *
     * @see https://docs.asaas.com/reference/list-transfers
     *
     * @param  array  $params  {
     *
     * @type string $dateCreatedLe[ge]  Filter by initial creation date
     * @type string $dateCreatedLe[le]  Filter by final creation date
     * @type string $transferDate[ge]   Filter by initial transfer date
     * @type string $transferDate[le]   Filter by final transfer date
     * @type string $type               Filter by transfer type
     *              }
     */
    public function list(array $params = []): ListResponse
    {
        return $this->get('transfers', $params);
    }

    /**
     * Find a transfer by ID
     *
     * @see https://docs.asaas.com/reference/retrieve-a-single-transfer
     */
    public function find(string $id): TransferResponse
    {
        return $this->get("transfers/{$id}");
    }

    /**
     * Cancel a transfer
     *
     * @see https://docs.asaas.com/reference/cancel-a-transfer
     */
    public function cancel(string $id): TransferResponse
    {
        return $this->delete("transfers/{$id}/cancel");
    }
}
