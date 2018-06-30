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
            'required' => 'required|boolean',
        ];

        if ('input' === $this->type) {
            $rules['configuration.min'] = 'required';
            $rules['configuration.max'] = 'required';
            $rules['configuration.default'] = 'nullable';
        }

        if ('selection' === $this->type) {
            $rules['configuration.values'] = 'required|array';
            $rules['configuration.default'] = 'nullable';
            $rules['configuration.multiple'] = 'required|boolean';
        }

        return $rules;
    }
}
