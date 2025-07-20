<?php

namespace Leopaulo88\Asaas\Resources;

use Leopaulo88\Asaas\Entities\Common\Deleted;
use Leopaulo88\Asaas\Entities\Common\Refund;
use Leopaulo88\Asaas\Entities\List\ListResponse;
use Leopaulo88\Asaas\Entities\Payment\BillingInfoResponse;
use Leopaulo88\Asaas\Entities\Payment\PaymentCreate;
use Leopaulo88\Asaas\Entities\Payment\PaymentCreditCard;
use Leopaulo88\Asaas\Entities\Payment\PaymentResponse;
use Leopaulo88\Asaas\Entities\Payment\PaymentUpdate;
use Leopaulo88\Asaas\Entities\Payment\StatusResponse;
use Leopaulo88\Asaas\Entities\Payment\ViewingInfoResponse;

class PaymentResource extends BaseResource
{
    /**
     * List payments.
     *
     * @see https://docs.asaas.com/reference/list-payments
     *
     * Available parameters in $params:
     * - installment (string): Filter by unique installment identifier
     * - offset (int): List starting element
     * - limit (int ≤ 100): Number of list elements (max: 100)
     * - customer (string): Filter by unique customer identifier
     * - customerGroupName (string): Filter by customer group name
     * - billingType (string): Filter by billing type
     * - status (string): Filter by status
     * - subscription (string): Filter by unique subscription identifier
     * - externalReference (string): Filter by your system identifier
     * - paymentDate (string): Filter by payment date
     * - invoiceStatus (string): Filter to return charges that have or do not have an invoice
     * - estimatedCreditDate (string): Filter by estimated credit date
     * - pixQrCodeId (string): Filter receipts originating from a static QrCode using the id generated when the QrCode was created
     * - anticipated (bool): Filter anticipated charges or not
     * - anticipable (bool): Filter anticipable charges or not
     * - dateCreated[ge] (string): Filter from initial creation date
     * - dateCreated[le] (string): Filter to final creation date
     * - paymentDate[ge] (string): Filter from initial payment date
     * - paymentDate[le] (string): Filter to final payment date
     * - estimatedCreditDate[ge] (string): Filter from estimated initial credit date
     * - estimatedCreditDate[le] (string): Filter to estimated end credit date
     * - dueDate[ge] (string): Filter from initial due date
     * - dueDate[le] (string): Filter by final due date
     * - user (string): Filter by the email address of the user who created the payment
     */
    public function list(array $params = []): ListResponse
    {
        return $this->get('/payments', $params);
    }

    /**
     * Create a new payment.
     *
     * @see https://docs.asaas.com/reference/create-new-payment
     *
     * For create a new payment with credit card, see documentation:
     * @see https://docs.asaas.com/reference/create-new-payment-with-credit-card
     */
    public function create(array|PaymentCreate $payment): PaymentResponse
    {
        if (is_array($payment)) {
            $payment = PaymentCreate::fromArray($payment);
        }

        return $this->post('/payments', $payment->toArray());
    }

    /**
     * Retrieve a single payment by ID.
     *
     * @see https://docs.asaas.com/reference/retrieve-a-single-payment
     */
    public function find(string $id): PaymentResponse
    {
        return $this->get("/payments/{$id}");
    }

    /**
     * Update an existing payment.
     *
     * @see https://docs.asaas.com/reference/update-existing-payment
     */
    public function update(string $id, array|PaymentUpdate $payment): PaymentResponse
    {
        if (is_array($payment)) {
            $payment = PaymentUpdate::fromArray($payment);
        }

        return $this->put("/payments/{$id}", $payment->toArray());

    }

    /**
     * Remove a payment.
     *
     * @see https://docs.asaas.com/reference/delete-payment
     *
     * @param  string  $id  The unique identifier of the payment to be removed.
     */
    public function remove(string $id): Deleted
    {
        $res = $this->delete("/payments/{$id}");

        return Deleted::fromArray($res);
    }

    /**
     * Restore a deleted payment.
     *
     * @see https://docs.asaas.com/reference/restore-removed-payment
     */
    public function restore(string $id): PaymentResponse
    {
        return $this->post("/payments/{$id}/restore");
    }

    /**
     * Capture an authorized payment.
     *
     * @see https://docs.asaas.com/reference/capture-payment-with-pre-authorization
     */
    public function captureAuthorizedPayment(string $id): PaymentResponse
    {
        return $this->post("/payments/{$id}/captureAuthorizedPayment");
    }

    /**
     * Pay a payment with credit card.
     *
     * @see https://docs.asaas.com/reference/pay-a-charge-with-credit-card
     */
    public function payWithCreditCard(string $id, array|PaymentCreditCard $creditCard): PaymentResponse
    {
        if (is_array($creditCard)) {
            $creditCard = PaymentCreditCard::fromArray($creditCard);
        }

        return $this->post("/payments/{$id}/payWithCreditCard", $creditCard->toArray());
    }

    /**
     * Retrieve billing information for a payment.
     *
     * @see https://docs.asaas.com/reference/retrieve-payment-billing-information
     */
    public function billingInfo(string $id): BillingInfoResponse
    {
        $res = $this->get("/payments/{$id}/billingInfo");

        return BillingInfoResponse::fromArray($res);
    }

    /**
     * Retrieve viewing information for a payment.
     *
     * @see https://docs.asaas.com/reference/payment-viewing-information
     */
    public function viewingInfo(string $id): ViewingInfoResponse
    {
        $res = $this->get("/payments/{$id}/viewingInfo");

        return ViewingInfoResponse::fromResponse($res);
    }

    /**
     * Retrieve the status of a payment.
     *
     * @see https://docs.asaas.com/reference/retrieve-status-of-a-payment
     */
    public function status(string $id): StatusResponse
    {
        $response = $this->get("/payments/{$id}/status");

        return StatusResponse::fromResponse($response);
    }

    /**
     * Refund a payment.
     *
     * @see https://docs.asaas.com/reference/refund-payment
     */
    public function refund(string $id, array|Refund $refund): PaymentResponse
    {
        if (is_array($refund)) {
            $refund = Refund::fromArray($refund);
        }

        return $this->post("/payments/{$id}/refund", $refund->toArray());
    }
}
