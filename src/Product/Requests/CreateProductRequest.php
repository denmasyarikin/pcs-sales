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
        return array_merge(
            $this->productRules(),
            $this->processRules(),
            $this->mediaRules(),
            $this->groupRules()
        );
    }

    /**
     * product rules.
     *
     * @return array
     */
    protected function productRules()
    {
        return [
            'name' => 'required|min:3|max:20',
            'description' => 'required|min:20|max:150',
            'unit_id' => 'required|exists:core_units,id',
            'min_order' => 'required|numeric|min:1',
            'customizable' => 'required|boolean',
            'base_price' => 'required|numeric',
            'per_unit_price' => 'required|numeric',
            'process_service_count' => 'required|numeric',
            'process_good_count' => 'required|numeric',
            'process_manual_count' => 'required|numeric',
            'status' => 'required|in:active,inactive',
        ];
    }

    /**
     * process rules.
     *
     * @param string $field
     * @param string $optionField
     * @param bool   $update
     *
     * @return array
     */
    protected function processRules($field = 'processes', $optionField = null, $update = false)
    {
        $field = $optionField ?: $field;

        $process = [
            $field => 'array'.((is_null($optionField) and !$update) ? '|required' : ''),
            "{$field}.*.process_type" => 'required|in:good,service,manual',
            "{$field}.*.process_type_as" => 'required|in:good,service',
            "{$field}.*.reference_id" => 'numeric',
            "{$field}.*.name" => 'required|min:3|max:20',
            "{$field}.*.type" => 'min:3|max:20',
            "{$field}.*.quantity" => 'required|numeric|min:1',
            "{$field}.*.base_price" => 'required|numeric',
            "{$field}.*.required" => 'boolean',
            "{$field}.*.static_price" => 'boolean',
            "{$field}.*.static_to_order_count" => "numeric|min:1|required_if:{$field}.*.static_price,false",
            "{$field}.*.unit_id" => 'required|exists:core_units,id',
        ];

        if (is_null($optionField)) {
            return array_merge($process, $this->processRules($field, $field.'.*.options', $update));
        }

        return $process;
    }

    /**
     * media rules.
     *
     * @param string $field
     *
     * @return array
     */
    protected function mediaRules($field = 'medias')
    {
        return [
            $field => 'array',
            "{$field}.*.type" => 'required|in:image,youtube',
            "{$field}.*.content" => 'required',
            "{$field}.*.sequence" => 'required|numeric',
            "{$field}.*.primary" => 'required|boolean',
        ];
    }

    /**
     * groups rules.
     *
     * @param string $field
     *
     * @return array
     */
    protected function groupRules($field = 'groups')
    {
        return [
            $field => 'array',
            "{$field}.*" => 'numeric|exists:sales_product_groups,id',
        ];
    }
}
