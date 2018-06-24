<?php

namespace Denmasyarikin\Sales\Product\Requests;

class UpdateProductProcessRequest extends DetailProductProcessRequest
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
