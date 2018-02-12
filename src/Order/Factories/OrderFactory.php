<?php

namespace Denmasyarikin\Sales\Order\Factories;

use App\Manager\Facades\Setting;
use Denmasyarikin\Sales\Order\Order;
use Denmasyarikin\Sales\Order\OrderItem;
use Denmasyarikin\Sales\Payment\Factory as PaymentFactory;

class OrderFactory
{
    /**
     * order.
     *
     * @var Order
     */
    protected $order;

    /**
     * settings.
     *
     * @var array
     */
    protected $settings;

    /**
     * Constructor.
     *
     * @param Order $order
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
        $this->settings = Setting::get('system.sales.order');
    }

    /**
     * create Order Item.
     *
     * @param array  $item
     * @param string $markup
     * @param string $discount
     * @param string $voucher
     *
     * @return OrderItem
     */
    public function createOrderItem(array $item, $markup = null, $discount = null, $voucher = null)
    {
        $item['total'] = $item['unit_total'];

        $orderItem = $this->order->items()->create($item);
        
        // if product process just store to db, they are not effected to the order
        if ($item['type'] === 'product' AND $item['type_as'] !== 'product') {
            return $orderItem;
        }

        $orderItem = $this->applyAdjustment($orderItem, $markup, $discount, $voucher);

        $this->updateOrderItemTotal();
        $this->resetOrderAdjustment();
        $this->resetOrderPayment();

        return $orderItem;
    }

    /**
     * update order item.
     *
     * @param OrderItem $orderItem
     * @param array     $item
     * @param string    $markup
     * @param string    $discount
     * @param string    $voucher
     *
     * @return OrderItem
     */
    public function updateOrderItem(OrderItem $orderItem, array $item, $markup = null, $discount = null, $voucher = null)
    {
        $item['total'] = $item['unit_total'];

        $orderItem->update($item);

        // if product process just store to db, they are not effected to the order
        if ($item['type'] === 'product' AND $item['type_as'] !== 'product') {
            return $orderItem;
        }

        $orderItem = $this->applyAdjustment($orderItem, $markup, $discount, $voucher);

        $this->updateOrderItemTotal();
        $this->resetOrderAdjustment();
        $this->resetOrderPayment();

        return $orderItem;
    }

    /**
     * apply adjustment.
     *
     * @param OrderItem $orderItem
     * @param string    $markup
     * @param string    $discount
     * @param string    $voucher
     *
     * @return OrderItem
     */
    protected function applyAdjustment(OrderItem $orderItem, $markup = null, $discount = null, $voucher = null)
    {
        if (!is_null($markup) AND $markup > 0) {
            $factory = new MarkupFactory($orderItem);
            $orderItem = $factory->apply($markup);
        } else {
            // remove markup
            $orderItem->adjustments()->whereType('markup')->delete();
        }

        if (!is_null($discount) AND $discount > 0) {
            $factory = new DiscountFactory($orderItem);
            $orderItem = $factory->apply($discount);
        } else {
            // remove discount
            $orderItem->adjustments()->whereType('discount')->delete();
        }

        if (!is_null($voucher) AND $voucher !== '') {
            $factory = new VoucherFactory($orderItem);
            $orderItem = $factory->apply($voucher);
        } else {
            // remove voucher
            $orderItem->adjustments()->whereType('voucher')->delete();
        }

        return $orderItem;
    }

    /**
     * update order item total.
     */
    protected function updateOrderItemTotal()
    {
        $this->order->item_total = 0;

        foreach ($this->order->getPrimaryItems() as $item) {
            $this->order->item_total += $item->total;
        }

        $this->order->save();
        $this->order->updateTotal();
    }

    /**
     * update order adjustment.
     */
    protected function resetOrderAdjustment()
    {
        // skip if no adjustments
        if (0 === count($this->order->getAdjustments())) {
            return;
        }

        $reseter = new AdjustmentReseter($this->order);
        $reseter->reset();
    }

    /**
     * update order payment.
     */
    protected function resetOrderPayment()
    {
        // skip if no payments
        if (0 === count($this->order->getPayments())) {
            return;
        }

        $factory = new PaymentFactory($this->order);
        $factory->resetAllPayment();
    }

    /**
     * delete order item.
     *
     * @param OrderItem $orderItem
     */
    public function deleteOrderItem(OrderItem $orderItem)
    {
        $orderItem->delete();

        $this->updateOrderItemTotal();
        $this->resetOrderAdjustment();
        $this->resetOrderPayment();
    }
}
