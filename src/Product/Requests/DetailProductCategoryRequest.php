<?php

namespace Denmasyarikin\Sales\Product\Requests;

use App\Http\Requests\FormRequest;
use Denmasyarikin\Sales\Product\ProductCategory;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DetailProductCategoryRequest extends FormRequest
{
    /**
     * product.group.
     *
     * @var ProductCategory
     */
    public $productCategory;

    /**
     * get product.
     *
     * @return Product
     */
    public function getProductCategory(): ?ProductCategory
    {
        if ($this->productCategory) {
            return $this->productCategory;
        }

        $id = (int) $this->route('id');

        if ($this->productCategory = ProductCategory::find($id)) {
            return $this->productCategory;
        }

        throw new NotFoundHttpException('Product Category Not Found');
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
