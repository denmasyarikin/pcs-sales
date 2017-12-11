<?php

namespace Denmasyarikin\Sales\Product\Requests;

class CreateProductProcessRequest extends DetailProductRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'parent_id' => 'exists:sales_product_processes,id',
            'type' => 'required|in:good,service,manual',
            'type_as' => 'required|in:good,service',
            'reference_id' => 'numeric',
            'name' => 'required|min:3|max:20',
            'specific' => 'min:3|max:20',
            'quantity' => 'required|numeric|min:1',
            'base_price' => 'required|numeric',
            'required' => 'boolean',
            'static_price' => 'boolean',
            'static_to_order_count' => 'numeric|min:1|required_if:static_price,false',
            'unit_id' => 'required|exists:core_units,id',
        ];
    }
}