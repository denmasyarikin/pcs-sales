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
     * process rules
     *
     * @var array
     */
    protected $processRules = [
        'parent_id' => 'nullable|exists:sales_product_processes,id',
        'type' => 'required|in:good,service,manual',
        'type_as' => 'required|in:good,service',
        'reference_id' => 'nullable|numeric',
        'reference_type' => 'nullable|required_with:reference_id',
        'name' => 'required|min:3|max:20',
        'specific' => 'nullable|min:3|max:20',
        'quantity' => 'required|numeric|min:1',
        'unit_price' => 'required|numeric',
        'unit_id' => 'required|exists:core_units,id',
        'required' => 'nullable|boolean',
        // dimension
        'depending_to_dimension' => 'nullable|boolean',
        'dimension' => 'nullable|required_if:depending_to_dimension,true|in:length,area,volume,wight',
        'dimension_unit_id' => 'nullable|required_if:depending_to_dimension,true|exists:core_units,id',
        'length' => 'nullable|required_if:dimension,length,area,volume|numeric',
        'width' => 'nullable|required_if:dimension,area,volume|numeric',
        'height' => 'nullable|required_if:dimension,volume|numeric',
        'weight' => 'nullable|required_if:dimension,wight|numeric',
        // increasement
        'price_type' => 'nullable|in:static,dynamic',
        'price_increase_multiples' => 'nullable|required_if:price_type,dynamic|numeric',
        'price_increase_percentage' => 'nullable|required_if:price_type,dynamic|numeric|min:1|max:100',
        // insheet
        'insheet_required' => 'nullable|boolean',
        'insheet_type' => 'nullable|required_if:insheet_required,true|in:static,dynamic',
        'insheet_multiples' => 'nullable|required_if:insheet_type,dynamic|numeric',
        'insheet_quantity' => 'nullable|required_if:insheet_required,true|numeric',
        'insheet_added' => 'nullable|required_if:insheet_required,true|numeric'
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
        return [];
    }
}
