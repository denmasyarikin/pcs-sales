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
     * process rules.
     *
     * @var array
     */
    protected $processRules = [
        'parent_id' => 'nullable|exists:sales_product_processes,id',
        'type' => 'required|in:good,service,manual',
        'reference_id' => 'nullable|numeric',
        'reference_type' => 'nullable|required_with:reference_id',
        'reference_default_id' => 'nullable',
        'reference_configurations' => 'nullable',
        'name' => 'required|max:30',
        'specific' => 'nullable|max:30',
        'quantity' => 'required|numeric|min:1',
        'unit_price' => 'required|numeric',
        'unit_id' => 'required|exists:core_units,id',
        'required' => 'nullable|boolean',
        'configurable' => 'nullable|boolean',
        'use_ratio' => 'nullable|boolean',
        'ratio_order_quantity' => 'nullable|required_if:use_ratio,true',
        'ratio_process_quantity' => 'nullable|required_if:use_ratio,true',
        // insheet
        'insheet_required' => 'nullable|boolean',
        'insheet_type' => 'nullable|required_if:insheet_required,true|in:static,dynamic',
        'insheet_multiples' => 'nullable|required_if:insheet_type,dynamic|numeric',
        'insheet_quantity' => 'nullable|required_if:insheet_required,true|numeric',
        'insheet_default' => 'nullable|required_if:insheet_required,true|numeric',
    ];

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

        $id = (int) $this->route('id');

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
