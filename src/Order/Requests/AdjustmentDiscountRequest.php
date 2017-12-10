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
            'percent' => 'required|numeric|max:100',
        ];
    }
}
