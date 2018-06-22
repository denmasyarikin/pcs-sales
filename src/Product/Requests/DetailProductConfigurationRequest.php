<?php

namespace Denmasyarikin\Sales\Product\Requests;

use Denmasyarikin\Sales\Product\ProductConfiguration;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DetailProductConfigurationRequest extends DetailProductRequest
{
    /**
     * productConfiguration.
     *
     * @var ProductConfiguration
     */
    public $productConfiguration;

    /**
     * get productConfiguration.
     *
     * @return ProductConfiguration
     */
    public function getProductConfiguration(): ?ProductConfiguration
    {
        if ($this->productConfiguration) {
            return $this->productConfiguration;
        }

        $product = $this->getProduct();
        $id = (int) $this->route('configuration_id');

        if ($this->productConfiguration = $product->configurations()->find($id)) {
            return $this->productConfiguration;
        }

        throw new NotFoundHttpException('Product Configuration Not Found');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [];
    }
}
