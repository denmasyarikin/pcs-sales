<?php

namespace Denmasyarikin\Sales\Order\Requests;

use Denmasyarikin\Sales\Order\Order;

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
        $orderItem = parent::getOrderItem($order);

        // only for item primary
        if (!$orderItem->isProductProcess()) {
            $this->checkFreshData($order);
        }

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
