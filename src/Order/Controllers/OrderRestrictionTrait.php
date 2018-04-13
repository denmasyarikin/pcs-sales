<?php

namespace Denmasyarikin\Sales\Order\Controllers;

use App\Manager\Facades\Setting;
use Denmasyarikin\Sales\Order\Order;
use Symfony\Component\Process\Exception\InvalidArgumentException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

trait OrderRestrictionTrait
{
    /**
     * has item totals.
     *
     * @param Order $order
     */
    protected function hasItems(Order $order)
    {
        if (0 === count($order->getItems())) {
            throw new BadRequestHttpException('Order has no item yet');
        }
    }

    /**
     * strict order item type.
     *
     * @param string $type
     * @param string $typeAs
     */
    protected function orderItemTypeRestriction($type, $typeAs)
    {
        switch ($type) {
            case 'good':
                if ('good' !== $typeAs) {
                    throw new BadRequestHttpException('Type As of type good only allowed good');
                }
                break;
            case 'service':
                if ('service' !== $typeAs) {
                    throw new BadRequestHttpException('Type As of type service only allowed service');
                }
                break;
            case 'manual':
                if (!in_array($typeAs, ['good', 'service'])) {
                    throw new BadRequestHttpException('Type As of type manual only allowed good or service');
                }
                break;
        }
    }

    /**
     * update status order restriction.
     *
     * @param Order  $order
     * @param string $status
     */
    protected function updateOrderStatusRetriction(Order $order, $status)
    {
        switch ($status) {
            case 'created':
                $allow = ('draft' === $order->status
                    and !is_null($order->customer)
                    and count($order->getItems()) > 0);
                break;

            case 'processing':
                $allow = 'created' === $order->status;
                break;

            case 'finished':
                $allow = 'processing' === $order->status;
                break;

            case 'closed':
                $allow = 'finished' === $order->status;
                break;

            default:
                $allow = false;
                break;
        }

        if (!$allow) {
            throw new BadRequestHttpException(
                "Can not update status to {$status}"
            );
        }
    }

    /**
     * updateable order.
     *
     * @param Order $order
     * @param bool  $includePayment
     */
    protected function updateableOrder(Order $order, $includePayment = true)
    {
        // only status draft and created are allowed
        if (!in_array($order->status, ['draft', 'created'])) {
            throw new BadRequestHttpException("Can not update order on status {$order->status}");
        }

        if (!$includePayment) {
            return true;
        }

        // and of course no payment
        if ($order->payments->count() > 0) {
            throw new BadRequestHttpException('Can not update order that has been paid');
        }
    }

    /**
     * updateable order.
     *
     * @param Order $order
     */
    protected function deletableOrder(Order $order)
    {
        // only status draft are deletable
        if ('draft' !== $order->status) {
            throw new BadRequestHttpException('Order can only be deleted, when status draft');
        }
    }

    /**
     * cancelable order.
     *
     * @param Order $order
     */
    protected function cancelableOrder(Order $order)
    {
        // canceled, draft or closed not cancelable
        if (in_array($order->status, ['draft', 'closed', 'canceled'])) {
            throw new BadRequestHttpException("Can not cancle order when status is {$order->status}");
        }
    }

    /**
     * order adjustment restriction.
     *
     * @param string $type
     */
    protected function orderAdjustmentRestriction($type)
    {
        if (!Setting::get('system.sales.order.enabled_adjustment')) {
            throw new BadRequestHttpException('No adjustments enabled');
        }

        switch ($type) {
            case 'voucher':
                $enabled = Setting::get('system.sales.order.enabled_voucher');
                break;

            case 'discount':
                $enabled = Setting::get('system.sales.order.enabled_discount');
                break;

            case 'tax':
                $enabled = Setting::get('system.sales.order.enabled_tax');
                break;

            default:
                throw new InvalidArgumentException('Invalid adjustment type');
                break;
        }

        if (!$enabled) {
            throw new BadRequestHttpException("{$type} adjustment is disabled");
        }
    }
}
