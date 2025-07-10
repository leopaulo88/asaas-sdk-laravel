<?php

namespace Hubooai\Asaas\Resources;

class PaymentResource extends BaseResource
{
    protected string $endpoint = '/payments';

    /**
     * List payments with optional filters
     */
    public function list(array $filters = []): array
    {
        return parent::list($filters);
    }

    /**
     * Create a new payment
     */
    public function create(array $data): array
    {
        return parent::create($data);
    }

    /**
     * Update payment information
     */
    public function update(string $id, array $data): array
    {
        return parent::update($id, $data);
    }

    /**
     * Get payment by ID
     */
    public function get(string $id): array
    {
        return parent::get($id);
    }

    /**
     * Delete a payment
     */
    public function delete(string $id): array
    {
        return parent::delete($id);
    }

    /**
     * Restore a deleted payment
     */
    public function restore(string $id): array
    {
        return $this->client->post("{$this->endpoint}/{$id}/restore");
    }

    /**
     * Refund a payment
     */
    public function refund(string $id, array $data = []): array
    {
        return $this->client->post("{$this->endpoint}/{$id}/refund", $data);
    }

    /**
     * Get payment status
     */
    public function getStatus(string $id): array
    {
        return $this->client->get("{$this->endpoint}/{$id}/status");
    }

    /**
     * Confirm cash payment
     */
    public function confirmCash(string $id, array $data = []): array
    {
        return $this->client->post("{$this->endpoint}/{$id}/receiveInCash", $data);
    }

    /**
     * Undo cash confirmation
     */
    public function undoCash(string $id): array
    {
        return $this->client->post("{$this->endpoint}/{$id}/undoReceivedInCash");
    }

    /**
     * Get payment installments
     */
    public function getInstallments(string $id): array
    {
        return $this->client->get("{$this->endpoint}/{$id}/installments");
    }

    /**
     * Get payment QR Code for PIX
     */
    public function getPixQrCode(string $id): array
    {
        return $this->client->get("{$this->endpoint}/{$id}/pixQrCode");
    }
}
