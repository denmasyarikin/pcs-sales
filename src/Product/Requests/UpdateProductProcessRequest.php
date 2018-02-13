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
        return $this->processRules;
    }
}
