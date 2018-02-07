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
            'base_price' => 'required|numeric',
            'required' => 'nullable|boolean',
            'static_price' => 'nullable|boolean',
            'static_to_order_count' => 'nullable|numeric|min:1|required_if:static_price,false',
            'unit_id' => 'required|exists:core_units,id',
        ];
    }
}
