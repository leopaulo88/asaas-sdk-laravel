<?php

namespace Leopaulo88\Asaas\Resources;

use Leopaulo88\Asaas\Entities\CreditCardToken\CreditCardTokenCreate;
use Leopaulo88\Asaas\Entities\CreditCardToken\CreditCardTokenResponse;

class CreditCardResource extends BaseResource
{
    /**
     * Tokenize a credit card.
     *
     * @see https://docs.asaas.com/reference/credit-card-tokenization
     */
    public function tokenize(array|CreditCardTokenCreate $data): CreditCardTokenResponse
    {
        if (is_array($data)) {
            $data = CreditCardTokenCreate::fromArray($data);
        }

        $response = $this->post('/creditCard/tokenizeCreditCard', $data->toArray());

        return CreditCardTokenResponse::fromArray($response);

    }
}
