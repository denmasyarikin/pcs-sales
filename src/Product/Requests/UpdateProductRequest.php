<?php

namespace Denmasyarikin\Sales\Product\Requests;

class UpdateProductRequest extends DetailProductRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|min:3|max:20',
            'description' => 'required|min:20|max:150',
            'unit_id' => 'required|exists:core_units,id',
            'min_order' => 'required|numeric|min:1',
            'order_multiples' => 'required|numeric|min:1',
            'status' => 'nullable|in:active,inactive,draft',
            'product_group_id' => 'nullable|exists:sales_product_groups,id',
        ];
    }
}
