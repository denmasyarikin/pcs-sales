<?php

namespace Denmasyarikin\Sales\Order\Requests;

class CreateOrderItemRequest extends DetailOrderRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return $this->itemRules;
    }
}
