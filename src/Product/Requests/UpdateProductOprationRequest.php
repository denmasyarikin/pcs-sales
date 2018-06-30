<?php

namespace Denmasyarikin\Sales\Product\Requests;

class UpdateProductOprationRequest extends DetailProductOprationRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'product_configuration_id' => 'required|exists:sales_product_configurations,id',
            'condition' => 'required|in:<,<=,=,!=,>,>=,in,not_in',
            'condition_value' => 'required',
            'opration' => 'required|in:visibility,ratio,requiration,insheet,service_configuration,order_counter'
        ];

        switch ($this->opration) {
            case 'visibility':
                $rules['opration_value'] = 'required|in:true,false';
                break;

            case 'ratio':
                $rules['opration_value'] = 'required|array';
                $rules['opration_value.ratio_order_quantity'] = 'required|numeric';
                $rules['opration_value.ratio_process_quantity'] = 'required|numeric';
                break;

            case 'requiration':
                $rules['opration_value'] = 'required|boolean';
                break;

            case 'insheet':
                $rules['opration_value'] = 'required|array';
                $rules['opration_value.good_insheet_multiples'] = 'required|numeric';
                $rules['opration_value.good_insheet_quantity'] = 'required|numeric';
                $rules['opration_value.good_insheet_default'] = 'required|numeric';
                break;

            case 'service_configuration':
                $rules['opration_value'] = 'required||array';
                break;

            case 'order_counter':
                $rules['opration_value'] = 'required|array';
                $rules['opration_value.min_order'] = 'required|numeric';
                $rules['opration_value.order_multiples'] = 'required|numeric';
                break;
        }

        return $rules;
    }
}
