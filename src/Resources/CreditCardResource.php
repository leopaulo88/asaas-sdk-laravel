<?php

namespace Leopaulo88\Asaas\Resources;

use Leopaulo88\Asaas\Entities\CreditCard\CreditCardCreate;
use Leopaulo88\Asaas\Entities\CreditCard\CreditCardResponse;

class CreditCardResource extends BaseResource
{
    public function create(array|CreditCardCreate $data)
    {
        if (is_array($data)) {
            $data = CreditCardCreate::fromArray($data);
        }

        $response = $this->post('/creditCard/tokenizeCreditCard', $data->toArray());

        return CreditCardResponse::fromArray($response);

    }
}
