<?php

namespace Denmasyarikin\Sales\Order\Requests;

use Denmasyarikin\Sales\Order\Order;
use Denmasyarikin\Sales\Order\OrderHistory;

class UpdateOrderHistoryRequest extends DetailOrderRequest
{
    /**
     * orderHistory.
     *
     * @var OrderHistory
     */
    public $orderHistory;

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
        $id = $this->route('history_id');

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
        return [
            'type' => 'required|in:order,process,payment,delivery',
            'label' => 'required',
        ];
    }
}
