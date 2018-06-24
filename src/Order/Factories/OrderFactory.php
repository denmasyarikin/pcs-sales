<?php

namespace Denmasyarikin\Sales\Order\Factories;

use App\Manager\Facades\Setting;
use Illuminate\Support\Facades\DB;
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
     * @param string $markupRule
     * @param string $discount
     * @param string $discountRule
     * @param string $voucher
     *
     * @return OrderItem
     */
    public function createOrderItem(array $item, $markup = null, $markupRule = null, $discount = null, $discountRule = null, $voucher = null)
    {
        try {
            DB::beginTransaction();

            $item['total'] = $item['unit_total'];

            $orderItem = $this->order->items()->create($item);

            $orderItem = $this->applyAdjustment($orderItem, $markup, $markupRule, $discount, $discountRule, $voucher);

            $this->updateOrderItemTotal();
            $this->resetOrderAdjustment();
            $this->resetOrderPayment();

            DB::commit();

            return $orderItem;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    /**
     * update order item.
     *
     * @param OrderItem $orderItem
     * @param array     $item
     * @param string    $markup
     * @param string    $markupRule
     * @param string    $discount
     * @param string    $discountRule
     * @param string    $voucher
     *
     * @return OrderItem
     */
    public function updateOrderItem(OrderItem $orderItem, array $item, $markup = null, $markupRule = null, $discount = null, $discountRule = null, $voucher = null)
    {
        try {
            DB::beginTransaction();

            $item['total'] = $item['unit_total'];

            $orderItem->update($item);

            $orderItem = $this->applyAdjustment($orderItem, $markup, $markupRule, $discount, $discountRule, $voucher);

            $this->updateOrderItemTotal();
            $this->resetOrderAdjustment();
            $this->resetOrderPayment();

            DB::commit();

            return $orderItem;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    /**
     * apply adjustment.
     *
     * @param OrderItem $orderItem
     * @param string    $markup
     * @param string    $markupRule
     * @param string    $discount
     * @param string    $discountRule
     * @param string    $voucher
     *
     * @return OrderItem
     */
    protected function applyAdjustment(OrderItem $orderItem, $markup = null, $markupRule = null, $discount = null, $discountRule = null, $voucher = null)
    {
        if (!is_null($markup) and !is_null($markupRule)) {
            $factory = new MarkupFactory($orderItem);
            $orderItem = $factory->apply($markup, $markupRule);
        }

        if (!is_null($discount) and !is_null($discountRule)) {
            $factory = new DiscountFactory($orderItem);
            $orderItem = $factory->apply($discount, $discountRule);
        }

        if (!is_null($voucher) and '' !== $voucher) {
            $factory = new VoucherFactory($orderItem);
            $orderItem = $factory->apply($voucher);
        }

        return $orderItem;
    }

    /**
     * update order item total.
     */
    protected function updateOrderItemTotal()
    {
        $this->order->item_total = 0;

        foreach ($this->order->getItems() as $item) {
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

        AdjustmentFactory::resetAdjustments($this->order);
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
        try {
            DB::beginTransaction();

            $orderItem->delete();

            $this->updateOrderItemTotal();
            $this->resetOrderAdjustment();
            $this->resetOrderPayment();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
}
