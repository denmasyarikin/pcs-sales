<?php

namespace Denmasyarikin\Sales\Order\Requests;

class AdjustmentTaxRequest extends DetailOrderRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'apply' => 'required|boolean',
        ];
    }
}
