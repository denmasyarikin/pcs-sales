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
     * @param string $markupType
     * @param string $discount
     * @param string $discountType
     * @param string $voucher
     *
     * @return OrderItem
     */
    public function createOrderItem(array $item, $markup = null, $markupType = null, $discount = null, $discountType = null, $voucher = null)
    {
        $item['total'] = $item['unit_total'];

        $orderItem = $this->order->items()->create($item);

        // if product process just store to db, they are not effected to the order
        if ('product' === $item['type'] and 'product' !== $item['type_as']) {
            return $orderItem;
        }

        $orderItem = $this->applyAdjustment($orderItem, $markup, $markupType, $discount, $discountType, $voucher);

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
     * @param string    $markupType
     * @param string    $discount
     * @param string    $discountType
     * @param string    $voucher
     *
     * @return OrderItem
     */
    public function updateOrderItem(OrderItem $orderItem, array $item, $markup = null, $markupType = null, $discount = null, $discountType = null, $voucher = null)
    {
        $item['total'] = $item['unit_total'];

        $orderItem->update($item);

        // if product process just store to db, they are not effected to the order
        if ('product' === $item['type'] and 'product' !== $item['type_as']) {
            return $orderItem;
        }

        $orderItem = $this->applyAdjustment($orderItem, $markup, $markupType, $discount, $discountType, $voucher);

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
     * @param string    $markupType
     * @param string    $discount
     * @param string    $discountType
     * @param string    $voucher
     *
     * @return OrderItem
     */
    protected function applyAdjustment(OrderItem $orderItem, $markup = null, $markupType = null, $discount = null, $discountType = null, $voucher = null)
    {
        if (!is_null($markup) and $markup > 0) {
            $factory = new MarkupFactory($orderItem);
            $orderItem = $factory->apply($markup, $markupType);
        } else {
            // remove markup
            $orderItem->adjustments()->whereType('markup')->delete();
        }

        if (!is_null($discount) and $discount > 0) {
            $factory = new DiscountFactory($orderItem);
            $orderItem = $factory->apply($discount, $discountType);
        } else {
            // remove discount
            $orderItem->adjustments()->whereType('discount')->delete();
        }

        if (!is_null($voucher) and '' !== $voucher) {
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

        // special case for product
        if ('product' === $orderItem->type) {
            if ('product' === $orderItem->type_as) {
                // also delete its process
                $this->order->items()
                    ->whereType('product')
                    ->where('type_as', '<>', 'product')
                    ->whereReferenceId($orderItem->id)
                    ->whereReferenceType('product')
                    ->delete();
            } else {
                // if product process they are not effected to the order
                return;
            }
        }

        $this->updateOrderItemTotal();
        $this->resetOrderAdjustment();
        $this->resetOrderPayment();
    }
}
