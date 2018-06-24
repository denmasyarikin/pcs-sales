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
        'type' => 'required|in:good,service,manual',
        'reference_id' => 'nullable|numeric',
        'reference_type' => 'nullable|required_with:reference_id',
        'reference_default_id' => 'nullable|integer',
        'reference_configurations' => 'nullable',
        'name' => 'required|max:30',
        'specific' => 'nullable|max:30',
        'quantity' => 'required|numeric|min:1',
        'unit_price' => 'required|numeric',
        'unit_total' => 'required|numeric',
        'unit_id' => 'required|exists:core_units,id',
        'required' => 'nullable|boolean',
        'ratio_order_quantity' => 'required|integer',
        'ratio_process_quantity' => 'required|integer|min:1',
        // insheet
        'good_insheet' => 'nullable|boolean',
        'good_insheet_multiples' => 'nullable|numeric',
        'good_insheet_quantity' => 'nullable|required_if:good_insheet,true|numeric',
        'good_insheet_default' => 'nullable|required_if:good_insheet,true|numeric',
        'service_configurable' => 'nullable|boolean'
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
