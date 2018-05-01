<?php

namespace Denmasyarikin\Sales\Order\Requests;

use Denmasyarikin\Sales\Order\Order;
use Denmasyarikin\Sales\Order\OrderAttachment;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UpdateOrderAttachmentRequest extends DetailOrderRequest
{
    /**
     * orderAttachment.
     *
     * @var OrderAttachment
     */
    public $orderAttachment;

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
     * get orderAttachment.
     *
     * @return OrderAttachment
     */
    public function getOrderAttachment(): ?OrderAttachment
    {
        if ($this->orderAttachment) {
            return $this->orderAttachment;
        }

        $order = $this->getOrder();
        $id = (int) $this->route('attachment_id');

        if ($this->orderAttachment = $order->attachments()->find($id)) {
            return $this->orderAttachment;
        }

        throw new NotFoundHttpException('Order Attachemnt Not Found');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'type' => 'required|in:po,sample',
            'file' => 'required',
            'description' => 'required',
        ];
    }
}
