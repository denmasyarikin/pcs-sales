<?php

namespace Denmasyarikin\Sales\Product\Requests;

use App\Http\Requests\FormRequest;

class CreateProductGroupRequest extends FormRequest
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
            'products.*' => 'exists:sales_products,id',
        ];
    }
}
