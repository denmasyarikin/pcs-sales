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
