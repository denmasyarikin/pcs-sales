<?php 

namespace Denmasyarikin\Sales\Order\Factories;

use App\Manager\Facades\Setting;
use Denmasyarikin\Sales\Order\Order;
use Denmasyarikin\Sales\Order\OrderItem;
use Denmasyarikin\Sales\Factories\AdjustmentReseter;

class OrderFactory
{
	/**
	 * order
	 *
	 * @var Order
	 */
	protected $order;

	/**
	 * settings
	 *
	 * @var array
	 */
	protected $settings;

	/**
	 * Constructor
	 *
	 * @param Order $order
	 * @return void
	 */
	public function __construct(Order $order)
	{
		$this->order = $order;
		$this->settings = Setting::get('system.sales.order');
	}

	/**
	 * create Order Item
	 *
	 * @param array $item
	 * @param string $markup
	 * @param string $discount
	 * @param string $voucher
	 * @return OrderItem
	 */
	public function createOrderItem(array $item, $markup = null, $discount = null, $voucher = null)
	{
		$item['unit_total'] = $item['total'] = $item['quantity'] * $item['unit_price'];

		$orderItem = $this->order->items()->create($item);
        $orderItem = $this->applyAdjustment($orderItem, $markup, $discount, $voucher);

        $this->updateOrderItemTotal($orderItem);
        $this->resetOrderAdjustment();

        return $orderItem; 
    }

    /**
     * update order item
     *
     * @param OrderItem $orderItem
     * @param array $item
     * @param string $markup
     * @param string $discount
     * @param string $voucher
     * @return OrderItem
     */
    public function updateOrderItem(OrderItem $orderItem, array $item, $markup = null, $discount = null, $voucher = null)
    {
		$item['unit_total'] = $item['quantity'] * $item['unit_price'];

        $orderItem->update($item);
		$orderItem = $this->applyAdjustment($orderItem, $markup, $discount, $voucher);

        $this->updateOrderItemTotal($orderItem);

		return $orderItem;
    }

    /**
     * apply adjustment
     *
     * @param OrderItem $orderItem
	 * @param string $markup
	 * @param string $discount
	 * @param string $voucher
     * @return OrderItem
     */
    protected function applyAdjustment(OrderItem $orderItem, $markup = null, $discount = null, $voucher = null)
    {
    	if (! is_null($markup)) {
            $factory = new MarkupFactory($orderItem);
            $orderItem = $factory->apply($markup);
        }

        if (! is_null($discount)) {
            $factory = new DiscountFactory($orderItem);
            $orderItem = $factory->apply($discount);
        }

        if (! is_null($voucher)) {
            $factory = new VoucherFactory($orderItem);
            $orderItem = $factory->apply($voucher);
        }

		return $orderItem;
    }

    /**
     * update order item total
     *
     * @param OrderItem $orderItem
     * @return void
     */
    protected function updateOrderItemTotal($orderItem)
    {
    	$this->order->item_total = 0;

    	foreach ($this->order->items as $item) {
    		$this->order->item_total += $item->total;
    	}

    	$this->order->save();
		$this->order->updateTotal();
    }

    /**
     * update order adjustment
     *
     * @param  
     * @return void
     */
    protected function resetOrderAdjustment()
    {
    	// skip if no adjustments
    	if (count($this->order->getAdjustments() === 0) {
    		return;
    	}

    	$reseter = new AdjustmentReseter($this->order);
    	$reseter->reset();
    }

    /**
     * delete order item
     *
     * @param OrderItem $orderItem
     * @return void
     */
    public function deleteOrderItem(OrderItem $orderItem)
    {
    	//
    }
}
