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
            'order_multiples' => 'required|numeric|min:1',
            'product_category_id' => 'nullable|exists:sales_product_categories,id',
            'workspace_ids' => 'required|array|min:1',
            'workspace_ids.*' => 'exists:core_workspaces,id',
        ];
    }
}
