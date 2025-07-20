<?php

namespace Leopaulo88\Asaas\Resources;

use Leopaulo88\Asaas\Entities\Common\Deleted;
use Leopaulo88\Asaas\Entities\Common\Split;
use Leopaulo88\Asaas\Entities\Installment\InstallmentCreate;
use Leopaulo88\Asaas\Entities\Installment\InstallmentResponse;
use Leopaulo88\Asaas\Entities\List\ListResponse;

class InstallmentResource extends BaseResource
{
    /**
     * Create a new installment.
     *
     * @see https://docs.asaas.com/reference/create-installment
     *
     * For create a new installment with credit card, see documentation:
     * @see https://docs.asaas.com/reference/create-installment-with-credit-card
     *
     * @param array|InstallmentCreate $installment
     * @return InstallmentResponse
     */
    public function create(array|InstallmentCreate $installment): InstallmentResponse
    {
        if (is_array($installment)) {
            $installment = InstallmentCreate::fromArray($installment);
        }

        return $this->post('installments', $installment->toArray());
    }

    /**
     * List installments.
     * @see https://docs.asaas.com/reference/list-installments
     * @param array $params{
     *  @type int $offset List starting element.
     *  @type int $limit Number of list elements (max: 100)
     * }
     * @return ListResponse
     */
    public function list(array $params = []): ListResponse
    {
        return $this->get('/installments', $params);
    }

    /**
     * Find an installment by ID.
     * @see https://docs.asaas.com/reference/retrieve-a-single-installment
     *
     * @param string $id The ID of the installment to find.
     * @return InstallmentResponse
     */
    public function find(string $id): InstallmentResponse
    {
        return $this->get("installments/{$id}");
    }

    /**
     * Remove an installment by ID.
     * @see https://docs.asaas.com/reference/remove-installment
     *
     * @param string $id The ID of the installment to remove.
     * @return Deleted
     */
    public function remove(string $id): Deleted
    {
        $res = $this->delete('installments/' . $id);
        return Deleted::fromArray($res);
    }

    /**
     * List payments for an installment.
     * @see https://docs.asaas.com/reference/list-payments-of-a-installment
     *
     * @param string $id The ID of the installment.
     * @param array $params{
     *  @type string $status Filter by payment status
     * }
     * @return ListResponse
     */
    public function listPayments(string $id, array $params = []): ListResponse
    {
        return $this->get("/installments/{$id}/payments", $params);
    }

    /**
     * Refund an installment.
     * @see https://docs.asaas.com/reference/refund-installment
     */
    public function refund(string $id): InstallmentResponse
    {
        return $this->post("installments/{$id}/refund");
    }

    /**
     * Update splits for an installment.
     * @see https://docs.asaas.com/reference/update-installment-splits
     *
     * @param string $id The ID of the installment.
     * @param Split[] $splits An array of splits to update.
     * @return Split[]
     */
    public function updateSplits(string $id, array $splits): array
    {
        $splitsArray = [];

        foreach ($splits as $split) {
            if (is_array($split)) {
                $split = Split::fromArray($split);
                $splitsArray[] = $split->toArray();
            } elseif ($split instanceof Split) {
                $splitsArray[] = $split->toArray();
            }
        }

        $res = $this->post("installments/{$id}/splits", ['splits' => $splitsArray]);

        $returnSplits = [];
        foreach (data_get($res, 'splits', []) as $split) {
            $returnSplits[] = Split::fromArray($split);
        }

        return $returnSplits;
    }

}
