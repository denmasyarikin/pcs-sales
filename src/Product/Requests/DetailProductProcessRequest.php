<?php

namespace Denmasyarikin\Sales\Product\Requests;

use Denmasyarikin\Sales\Product\ProductProcess;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DetailProductProcessRequest extends DetailProductRequest
{
    /**
     * productProcess.
     *
     * @var ProductProcess
     */
    public $productProcess;

    /**
     * get productProcess.
     *
     * @return ProductProcess
     */
    public function getProductProcess(): ?ProductProcess
    {
        if ($this->productProcess) {
            return $this->productProcess;
        }

        $product = $this->getProduct();
        $id = $this->route('process_id');

        if ($this->productProcess = $product->processes()->find($id)) {
            return $this->productProcess;
        }

        throw new NotFoundHttpException('Product Process Not Found');
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
