<?php

namespace Denmasyarikin\Sales\Order\Requests;

class AdjustmentDiscountRequest extends DetailOrderRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'type' => 'required|in:percentage,amount',
            'value' => 'required|numeric',
        ];
    }
}
