<?php

namespace Denmasyarikin\Sales\Product\Requests;

use App\Http\Requests\FormRequest;
use Denmasyarikin\Sales\Product\ProductGroup;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DetailProductGroupRequest extends FormRequest
{
    /**
     * product.group.
     *
     * @var ProductGroup
     */
    public $productGroup;

    /**
     * get product.
     *
     * @return Product
     */
    public function getProductGroup(): ?ProductGroup
    {
        if ($this->productGroup) {
            return $this->productGroup;
        }

        $id = $this->route('id');

        if ($this->productGroup = ProductGroup::find($id)) {
            return $this->productGroup;
        }

        throw new NotFoundHttpException('Product Group Not Found');
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
