<?php

namespace Denmasyarikin\Sales\Product\Requests;

use App\Http\Requests\FormRequest;

class CreateProductCategoryRequest extends FormRequest
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
            'image' => 'nullable',
            'parent_id' => 'nullable|numeric|exists:sales_product_categories,id',
            'workspace_ids' => 'required|array|min:1',
            'workspace_ids.*' => 'exists:core_workspaces,id',
        ];
    }
}
