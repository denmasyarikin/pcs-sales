<?php

namespace Denmasyarikin\Sales\Product\Requests;

use Denmasyarikin\Sales\Product\Product;

class UpdateProductProcessRequest extends DetailProductProcessRequest
{
    /**
     * get product.
     *
     * @return Product
     */
    public function getProduct(): ?Product
    {
        $product = parent::getProduct();

        $this->checkFreshData($product);

        return $product;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'parent_id' => 'nullable|exists:sales_product_processes,id',
            'type' => 'required|in:good,service,manual',
            'type_as' => 'required|in:good,service',
            'reference_id' => 'nullable|numeric',
            'name' => 'required|min:3|max:20',
            'specific' => 'nullable|min:3|max:20',
            'quantity' => 'required|numeric|min:1',
            'unit_price' => 'required|numeric',
            'unit_id' => 'required|exists:core_units,id',
            'required' => 'nullable|boolean',
            'price_type' => 'nullable|in:static,dynamic',
            'price_increase_multiples' => 'nullable|required_if:price_type,dynamic|numeric',
            'price_increase_percentage' => 'nullable|required_if:price_type,dynamic|numeric|min:1|max:100',
            'insheet_required' => 'nullable|boolean',
            'insheet_type' => 'nullable|required_if:insheet_required,true|in:static,dynamic,percentage',
            'insheet_value' => 'nullable|required_if:insheet_required,true|numeric',
        ];
    }
}
