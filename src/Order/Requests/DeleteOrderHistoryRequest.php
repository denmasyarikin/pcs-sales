<?php

namespace Denmasyarikin\Sales\Order\Requests;

use Denmasyarikin\Sales\Order\OrderHistory;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DeleteOrderHistoryRequest extends DetailOrderRequest
{
    /**
     * orderHistory.
     *
     * @var OrderHistory
     */
    public $orderHistory;

    /**
     * get orderHistory.
     *
     * @return OrderHistory
     */
    public function getOrderHistory(): ?OrderHistory
    {
        if ($this->orderHistory) {
            return $this->orderHistory;
        }

        $order = $this->getOrder();
        $id = (int) $this->route('history_id');

        if ($this->orderHistory = $order->histories()->find($id)) {
            return $this->orderHistory;
        }

        throw new NotFoundHttpException('Order History Not Found');
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
