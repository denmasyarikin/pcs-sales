<?php 

namespace Denmasyarikin\Sales\Order\Factories;

use App\Manager\Facades\Setting;
use Denmasyarikin\Sales\Order\Order;

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
		$item['adjustment_total'] = 0;
		$item['unit_total'] = $item['total'] = $item['quantity'] * $item['unit_price'];

		$orderItem = $this->order->items()->create($item);

        if (! is_null($markup)) {
            $factory = new MarkupFactory($orderItem);
            $factory->applyMarkup((float) $markup);
        }

        if (! is_null($discount)) {
            $factory = new DiscountFactory($orderItem);
            $factory->applyDiscount((float) $discount);
        }

        if (! is_null($voucher)) {
            $factory = new VoucherFactory($orderItem);
            $factory->applyVoucher($voucher);
        }

		$this->order->item_total += $orderItem->total;
		$this->order->save();

		$this->order->updateTotal();
		
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
        $item['adjustment_total'] = 0;
        $item['unit_total'] = $item['total'] = $item['quantity'] * $item['unit_price'];
    }
}
