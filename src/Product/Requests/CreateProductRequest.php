<?php

namespace Denmasyarikin\Sales\Product\Requests;

use App\Http\Requests\FormRequest;

class CreateProductRequest extends FormRequest
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
            'description' => 'required|min:10|max:100',
            'unit_id' => 'required|exists:core_units,id',
            'min_order' => 'required|numeric|min:1',
            'customizable' => 'required|boolean',
            'product_group_id' => 'exists:sales_product_groups,id',
        ];
    }
}
