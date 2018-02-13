<?php

namespace Denmasyarikin\Sales\Order\Requests;

use Denmasyarikin\Sales\Order\Order;
use Denmasyarikin\Sales\Order\OrderItem;

class UpdateOrderItemRequest extends DetailOrderItemRequest
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
        return $this->itemRules;
    }
}
