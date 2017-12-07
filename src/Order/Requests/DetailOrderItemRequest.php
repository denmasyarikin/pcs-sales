<?php

namespace Denmasyarikin\Sales\Order\Requests;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DetailOrderItemRequest extends DetailOrderRequest
{
    /**
     * orderItem.
     *
     * @var OrderItem
     */
    public $orderItem;

    /**
     * get orderItem.
     *
     * @return OrderItem
     */
    public function getOrderItem(): ?OrderItem
    {
        if ($this->orderItem) {
            return $this->orderItem;
        }

        $order = $this->getOrder();
        $id = $this->route('item_id');

        if ($this->orderItem = $order->items()->find($id)) {
            return $this->orderItem;
        }

        throw new NotFoundHttpException('Order Item Not Found');
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
