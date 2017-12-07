<?php

namespace Denmasyarikin\Sales\Order\Requests;

class AdjustmentVoucherRequest extends DetailOrderRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'code' => 'required|size:8|voucher'
        ];
    }
}
