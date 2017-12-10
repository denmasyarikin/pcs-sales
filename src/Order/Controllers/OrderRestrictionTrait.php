<?php

namespace Denmasyarikin\Sales\Order\Controllers;

use Denmasyarikin\Sales\Order\Order;
use Symfony\Component\Process\Exception\InvalidArgumentException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

trait OrderRestrictionTrait
{
	/**
	 * updateable order
	 *
	 * @param  
	 * @return void
	 */
	protected function updateableOrder(Order $order)
	{
		if (! in_array($order->status, ['draft', 'created'])) {
            throw new BadRequestHttpException("Can not update order on status {$order->status}");
        }
	}

	/**
     * has item totals
     *
     * @param Order $order
     * @return void
     */
    protected function hasItems(Order $order)
    {
        if (count($order->getItems()) === 0) {
            throw new BadRequestHttpException('Order has no item yet');
        }
    }

    /**
     * strict order item type
     *
     * @param string $type
     * @param string $typeAs
     * @return void
     */
    protected function orderItemTypeRestriction($type, $typeAs)
    {
        switch ($type) {
            case 'good':
                if ($typeAs !== 'good') {
                    throw new BadRequestHttpException('Type As of type good only allowed good');
                }
                break;
            case 'service':
                if ($typeAs !== 'service') {
                    throw new BadRequestHttpException('Type As of type service only allowed service');
                }
            case 'manual':
                if (! in_array($typeAs, ['good', 'service'])) {
                    throw new BadRequestHttpException('Type As of type manual only allowed good or service');
                }
                break;
        }
    }

    /**
     * update status order restriction
     *
     * @param Order $order
     * @param string $status
     * @return void
     */
    protected function updateOrderStatusRetriction(Order $order, $status)
    {
        switch ($status) {
            case 'created':
                $allow = ($order->status === 'draft' AND count($order->getItems()) > 0);
                break;

            case 'processing':
                $allow = $order->status === 'created';
                break;

            case 'finished':
                $allow = $order->status === 'processing';
                break;

            case 'archived':
                $allow = $order->status === 'finished';
                break;

            default:
                throw new InvalidArgumentException("Invalid status");
                break;
        }

        if (! $allow) {
            throw new BadRequestHttpException(
                "Can not update status to {$status}"
            );
        }
    }
}