<?php

namespace Leopaulo88\Asaas\Resources;

use Leopaulo88\Asaas\Entities\Common\Deleted;
use Leopaulo88\Asaas\Entities\List\ListResponse;
use Leopaulo88\Asaas\Entities\Subscription\SubscriptionCreate;
use Leopaulo88\Asaas\Entities\Subscription\SubscriptionResponse;
use Leopaulo88\Asaas\Entities\Subscription\SubscriptionUpdate;
use Leopaulo88\Asaas\Entities\Subscription\SubscriptionUpdateCreditCard;

class SubscriptionResource extends BaseResource
{
    /**
     * Create a new subscription.
     *
     * @see https://docs.asaas.com/reference/create-new-subscription
     *
     * For create a new subscription with credit card, see documentation:
     * @see https://docs.asaas.com/reference/create-subscription-with-credit-card
     */
    public function create(array|SubscriptionCreate $subscription): SubscriptionResponse
    {
        if (is_array($subscription)) {
            $subscription = SubscriptionCreate::fromArray($subscription);
        }

        return $this->post('/subscriptions', $subscription->toArray());
    }

    /**
     * List subscriptions.
     *
     * @see https://docs.asaas.com/reference/list-subscriptions
     *
     * @param  array  $params  {
     *
     * @type int $offset           List starting element.
     * @type int $limit            Number of list elements (max: 100).
     * @type string $customer         Filter by Unique Customer Identifier.
     * @type string $customerGroupName Filter by customer group name.
     * @type string $billingType      Filter by billing type.
     * @type string $status           Filter by status.
     * @type string $deletedOnly      Send true to return only removed subscriptions.
     * @type string $includeDeleted   Send true to also recover removed subscriptions.
     * @type string $externalReference Filter by your system identifier.
     * @type string $order            Ascending or descending order.
     * @type string $sort             Which field will it be sorted by.
     *              }
     */
    public function list(array $params = []): ListResponse
    {
        return $this->get('/subscriptions', $params);
    }

    /**
     * Find a subscription by ID.
     *
     * @see https://docs.asaas.com/reference/retrieve-a-single-subscription
     */
    public function find(string $id): SubscriptionResponse
    {
        return $this->get("/subscriptions/{$id}");
    }

    /**
     * Update an existing subscription.
     *
     * @see https://docs.asaas.com/reference/update-existing-subscription
     */
    public function update(string $id, array|SubscriptionUpdate $subscription): SubscriptionResponse
    {

        if (is_array($subscription)) {
            $subscription = SubscriptionUpdate::fromArray($subscription);
        }

        return $this->put("/subscriptions/{$id}", $subscription->toArray());
    }

    /**
     * Remove a subscription.
     * @see https://docs.asaas.com/reference/remove-subscription
     *
     * @param string $id The ID of the subscription to remove.
     * @return Deleted
     */
    public function remove(string $id): Deleted
    {
        $res = parent::delete("/subscriptions/{$id}");

        return Deleted::fromArray($res);
    }

    /**
     * Update the credit card of a subscription.
     *
     * @see https://docs.asaas.com/reference/update-credit-card-of-subscription
     */
    public function updateCreditCard(string $id, array|SubscriptionUpdateCreditCard $creditCard): SubscriptionResponse
    {
        if (is_array($creditCard)) {
            $creditCard = SubscriptionUpdateCreditCard::fromArray($creditCard);
        }

        return $this->put("/subscriptions/{$id}/creditCard", $creditCard->toArray());
    }

    /**
     * List payments of a subscription.
     *
     * @see https://docs.asaas.com/reference/list-payments-of-a-subscription
     *
     * @param  array  $params  {
     *
     * @type string $status Filter by status.
     *              }
     */
    public function listPayments(string $id, array $params = []): ListResponse
    {
        return $this->get("/subscriptions/{$id}/payments", $params);
    }
}
