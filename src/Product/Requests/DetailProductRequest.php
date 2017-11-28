<?php

namespace Denmasyarikin\Sales\Product\Requests;

use App\Http\Requests\FormRequest;
use Denmasyarikin\Sales\Product\Product;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DetailProductRequest extends FormRequest
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

        // this is better way but not working in lumen
        // $id = $this->route('id');

        $id = $this->segment(3);

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
        return [];
    }
}
