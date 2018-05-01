<?php

namespace Denmasyarikin\Sales\Order\Requests;

use Denmasyarikin\Sales\Order\OrderAttachment;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DeleteOrderAttachmentRequest extends DetailOrderRequest
{
    /**
     * orderAttachment.
     *
     * @var OrderAttachment
     */
    public $orderAttachment;

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

        throw new NotFoundHttpException('Order Attachment Not Found');
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
