<?php

namespace Denmasyarikin\Sales\Order\Controllers;

use Denmasyarikin\Sales\Order\Order;
use Symfony\Component\Process\Exception\InvalidArgumentException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

trait OrderRestrictionTrait
{
    /**
     * updateable order.
     *
     * @param
     */
    protected function updateableOrder(Order $order)
    {
        if (!in_array($order->status, ['draft', 'created'])) {
            throw new BadRequestHttpException("Can not update order on status {$order->status}");
        }
    }

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
                $allow = ('draft' === $order->status and count($order->getItems()) > 0);
                break;

            case 'processing':
                $allow = 'created' === $order->status;
                break;

            case 'finished':
                $allow = 'processing' === $order->status;
                break;

            case 'archived':
                $allow = 'finished' === $order->status;
                break;

            default:
                throw new InvalidArgumentException('Invalid status');
                break;
        }

        if (!$allow) {
            throw new BadRequestHttpException(
                "Can not update status to {$status}"
            );
        }
    }
}
