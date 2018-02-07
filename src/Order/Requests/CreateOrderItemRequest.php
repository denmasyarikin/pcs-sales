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
        return [
            'type' => 'required|in:product,service,good,manual',
            'type_as' => 'required|in:product,service,good,manual',
            'reference_id' => 'nullable|numeric',
            'name' => 'required|min:3|max:50',
            'specific' => 'nullable|min:3|max:50',
            'note' => 'nullable|min:3',
            'quantity' => 'required|integer',
            'unit_price' => 'required|numeric',
            'unit_id' => 'required|exists:core_units,id',
            'markup' => 'nullable|numeric|max:100',
            'discount' => 'nullable|numeric|max:100',
            'voucher' => 'nullable|size:8|voucher',
        ];
    }
}
