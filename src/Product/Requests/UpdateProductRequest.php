<?php

namespace Denmasyarikin\Sales\Product\Requests;

use Denmasyarikin\Sales\Product\Product;

class UpdateProductRequest extends DetailProductRequest
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
            'name' => 'required|min:3|max:20',
            'description' => 'required|min:20|max:150',
            'unit_id' => 'required|exists:core_units,id',
            'min_order' => 'required|numeric|min:1',
            'customizable' => 'required|boolean',
            'status' => 'nullable|in:active,inactive,draft',
            'product_group_id' => 'nullable|exists:sales_product_groups,id',
        ];
    }
}
