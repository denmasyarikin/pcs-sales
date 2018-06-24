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
        return $this->processRules;
    }
}
