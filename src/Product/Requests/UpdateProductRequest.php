<?php

namespace Denmasyarikin\Sales\Product\Requests;

use Denmasyarikin\Sales\Product\Product;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UpdateProductRequest extends CreateProductRequest
{
    /**
     * product.
     *
     * @var Product
     */
    public $product;

    /**
     * get product.
     *
     * @return Product
     */
    public function getProduct(): ?Product
    {
        if ($this->product) {
            return $this->product;
        }

        $id = $this->route('id');

        if ($this->product = Product::find($id)) {
            return $this->product;
        }

        throw new NotFoundHttpException('Product Not Found');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return array_merge($this->productRules(), [
            'processes' => 'array',
            'processes.remove' => 'array',
            'processes.remove.*' => 'numeric|exists:sales_product_processes,id',
        ], $this->processRules('processes.add', null, true), [
            'medias' => 'array',
            'medias.remove' => 'array',
            'medias.remove.*' => 'numeric|exists:sales_product_medias,id',
        ], $this->mediaRules('media.add'), [
            'groups' => 'array',
            'groups.remove' => 'array',
            'groups.remove.*' => 'numeric|exists:sales_product_groups,id',
        ], $this->groupRules('groups.add'));
    }
}
