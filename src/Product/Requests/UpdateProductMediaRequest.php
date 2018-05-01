<?php

namespace Denmasyarikin\Sales\Product\Requests;

use Denmasyarikin\Sales\Product\ProductMedia;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UpdateProductMediaRequest extends DetailProductRequest
{
    /**
     * productMedia.
     *
     * @var ProductMedia
     */
    public $productMedia;

    /**
     * get productMedia.
     *
     * @return ProductMedia
     */
    public function getProductMedia(): ?ProductMedia
    {
        if ($this->productMedia) {
            return $this->productMedia;
        }

        $product = $this->getProduct();
        $id = (int) $this->route('media_id');

        if ($this->productMedia = $product->medias()->find($id)) {
            return $this->productMedia;
        }

        throw new NotFoundHttpException('Product Media Not Found');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'type' => 'required|in:image,youtube',
            'content' => 'required',
            'sequence' => 'required|numeric',
            'primary' => 'required|boolean',
        ];
    }
}
