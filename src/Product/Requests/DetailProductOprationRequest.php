<?php

namespace Denmasyarikin\Sales\Product\Requests;

use Denmasyarikin\Sales\Product\ProductOpration;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DetailProductOprationRequest extends DetailProductProcessRequest
{
    /**
     * productOpration.
     *
     * @var ProductOpration
     */
    public $productOpration;

    /**
     * get productOpration.
     *
     * @return ProductOpration
     */
    public function getProductOpration(): ?ProductOpration
    {
        if ($this->productOpration) {
            return $this->productOpration;
        }

        $process = $this->getProductProcess();
        $id = (int) $this->route('process_id');

        if ($this->productOpration = $process->productOprations()->find($id)) {
            return $this->productOpration;
        }

        throw new NotFoundHttpException('Product Opration Not Found');
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
