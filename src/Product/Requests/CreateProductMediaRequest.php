<?php

namespace Denmasyarikin\Sales\Product\Requests;

class CreateProductMediaRequest extends DetailProductRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'type' => 'required|in:image,youtube',
            'content' => 'required',
            'sequence' => 'required|numeric',
            'primary' => 'required|boolean'
        ];
    }
}
