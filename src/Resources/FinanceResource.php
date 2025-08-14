<?php

namespace Leopaulo88\Asaas\Resources;

use Leopaulo88\Asaas\Entities\Finance\BalanceResponse;
use Leopaulo88\Asaas\Entities\Finance\SplitStatisticResponse;
use Leopaulo88\Asaas\Entities\Finance\StatisticResponse;

class FinanceResource extends BaseResource
{
    /**
     * Get the balance of the account.
     *
     * @see https://docs.asaas.com/reference/retrieve-account-balance
     */
    public function balance(): BalanceResponse
    {
        $response = $this->get('/finance/balance');

        return BalanceResponse::fromArray($response);
    }

    /**
     * Get the account statistics.
     *
     * @see https://docs.asaas.com/reference/billing-statistics
     */
    public function statistics(array $params = []): StatisticResponse
    {
        $response = $this->get('/finance/payment/statistics', $params);

        return StatisticResponse::fromArray($response);
    }

    /**
     * Get Split Statistics
     *
     * @see https://docs.asaas.com/reference/retrieve-split-values
     */
    public function splitStatistics(): SplitStatisticResponse
    {
        $response = $this->get('/finance/split/statistics');

        return SplitStatisticResponse::fromArray($response);
    }
}
