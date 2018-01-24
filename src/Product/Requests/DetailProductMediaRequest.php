<?php

namespace Denmasyarikin\Sales\Product\Requests;

use Denmasyarikin\Sales\Product\ProductMedia;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DetailProductMediaRequest extends DetailProductRequest
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
        $id = $this->route('media_id');

        if ($this->productMedia = $product->medias()->find($id)) {
            return $this->productMedia;
        }

        throw new NotFoundHttpException('Product Media Not Found');
    }
}
