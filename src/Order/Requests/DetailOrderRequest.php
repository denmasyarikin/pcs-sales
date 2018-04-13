<?php

namespace Denmasyarikin\Sales\Order\Requests;

use App\Http\Requests\FormRequest;
use Denmasyarikin\Sales\Order\Order;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DetailOrderRequest extends FormRequest
{
    /**
     * order.
     *
     * @var Order
     */
    public $order;

    /**
     * item validation rules.
     *
     * @var array
     */
    protected $itemRules = [
        'type' => 'required|in:product,service,good,manual',
        'type_as' => 'required|in:product,service,good,manual',
        'reference_id' => 'nullable|numeric',
        'reference_type' => 'nullable|required_with:reference_id',
        'reference_second_id' => 'nullable|numeric',
        'name' => 'required|min:3|max:50',
        'specific' => 'nullable|min:3|max:50',
        'note' => 'nullable|min:3',
        'quantity' => 'required|integer',
        'unit_price' => 'required|numeric',
        'unit_total' => 'required|numeric',
        'unit_id' => 'required|exists:core_units,id',
        'markup' => 'nullable|numeric',
        'markup_type' => 'nullable|in:percentage,amount',
        'discount' => 'nullable|numeric',
        'discount_type' => 'nullable|in:percentage,amount',
        'voucher' => 'nullable|size:8|voucher',
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
        'insheet_added' => 'nullable|required_if:insheet_required,true|numeric',
    ];

    /**
     * get order.
     *
     * @return Order
     */
    public function getOrder(): ?Order
    {
        if ($this->order) {
            return $this->order;
        }

        $id = $this->route('id');

        if ($this->order = Order::find($id)) {
            return $this->order;
        }

        throw new NotFoundHttpException('Order Not Found');
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
