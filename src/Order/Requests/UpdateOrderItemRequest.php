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
        return [
            'type' => 'required|in:product,service,good,manual',
            'type_as' => 'required|in:product,service,good,manual',
            'reference_id' => 'numeric',
            'name' => 'required|min:3|max:50',
            'specific' => 'min:3|max:50',
            'note' => 'min:3',
            'quantity' => 'required|integer',
            'unit_price' => 'required|numeric',
            'unit_id' => 'required|exists:core_units,id',
            'markup' => 'numeric|min:0|max:100',
            'discount' => 'numeric|min:0|max:100',
            'voucher' => 'size:8|voucher',
        ];
    }
}