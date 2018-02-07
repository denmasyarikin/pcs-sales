<?php

namespace Denmasyarikin\Sales\Order\Requests;

use Denmasyarikin\Sales\Order\Order;

class UpdateOrderRequest extends DetailOrderRequest
{
    /**
     * get order.
     *
     * @return Order
     */
    public function getOrder(): ?Order
    {
        $order = parent::getOrder();

        $this->checkFreshData($order);

        return $order;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'note' => 'nullable',
            'due_date' => 'required|date_format:Y-m-d H:i:s',
        ];
    }
}
