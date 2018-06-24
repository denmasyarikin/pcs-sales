<?php

namespace Denmasyarikin\Sales\Product\Requests;

class CreateProductConfigurationRequest extends DetailProductRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'name' => 'required|max:30',
            'type' => 'required|in:input,selection',
            'configuration' => 'required|array',
            'required' => 'required|boolean'
        ];


        if ($this->type === 'input') {
            $rules['configuration.min'] = 'required';
            $rules['configuration.max'] = 'required';
        }

        if ($this->type === 'selection') {
            $rules['configuration.values'] = 'required|array';
            $rules['configuration.multiple'] = 'required|boolean';
        }

        return $rules;
    }
}
