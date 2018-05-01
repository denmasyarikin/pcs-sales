<?php

namespace Denmasyarikin\Sales\Order\Requests;

class UpdateOrderItemRequest extends DetailOrderItemRequest
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
