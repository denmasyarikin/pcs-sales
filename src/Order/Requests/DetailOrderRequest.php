<?php

namespace Denmasyarikin\Sales\Order\Requests;

use Modules\Chanel\Chanel;
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
        'parent_id' => 'nullable|numeric|exists:sales_order_items,id',
        'reference_id' => 'nullable|numeric',
        'reference_type' => 'nullable|required_with:reference_id',
        'reference_configurations' => 'nullable',
        'name' => 'required|max:50',
        'specific' => 'nullable|max:50',
        'quantity' => 'required|integer',
        'unit_price' => 'required|numeric',
        'unit_total' => 'required|numeric',
        'note' => 'nullable',
        'unit_id' => 'required|exists:core_units,id',
        'markup' => 'nullable|numeric',
        'markup_rule' => 'nullable|in:fixed,percentage',
        'discount' => 'nullable|numeric',
        'discount_rule' => 'nullable|in:fixed,percentage',
        'voucher' => 'nullable|size:8|voucher',
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

        $order = null;
        $id = (int) $this->route('id');

        if (Order::isCode($id)) {
            $ids = Order::getIdFromCode($id);
            $order = Order::whereHas('chanel', function ($chanel) use ($ids) {
                $chanelIds = Chanel::getIdFromCode($ids['chanel_code']);
                $chanel->whereType($chanelIds['type']);
                $chanel->whereId($chanelIds['id']);
            })->whereCsUserId($ids['cs_user_id'])->find($ids['id']);
        } else {
            $order = Order::find($id);
        }

        if ($this->order = $order) {
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
