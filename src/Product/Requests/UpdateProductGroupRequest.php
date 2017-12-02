<?php

namespace Denmasyarikin\Sales\Product\Requests;

class UpdateProductGroupRequest extends DetailProductGroupRequest
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
            'status' => 'in:active,inactive',
            'parent_id' => 'exists:sales_product_groups,id',
            'products' => 'array',
            'products.remove' => 'array',
            'products.add' => 'array',
            'products.remove.*' => 'exists:sales_products,id',
            'products.add.*' => 'exists:sales_products,id',
        ];
    }
}
