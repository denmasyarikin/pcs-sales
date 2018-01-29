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
        ];
    }
}
