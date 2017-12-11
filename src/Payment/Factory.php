<?php

namespace Denmasyarikin\Sales\Payment;

use Illuminate\Support\Facades\Auth;
use Denmasyarikin\Sales\Order\Order;
use Denmasyarikin\Sales\Payment\Payment;
use Denmasyarikin\Sales\Order\OrderHistory;

class Factory
{
	/**
	 * customer service
	 *
	 * @var User
	 */
	protected $cs;

	/**
	 * order
	 *
	 * @var Order
	 */
	protected $order;

	/**
	 * payments
	 *
	 * @var Collection
	 */
	protected $payments;

	/**
	 * Create a new Constructor instance.
	 *
	 * @param Order $order
	 * @return void
	 */
	public function __construct(Order $order)
	{
		$this->order = $order;
		$this->cs = Auth::user();
		$this->payments = $order->payments;
	}

	/**
	 * pay order
	 *
	 * @param array $data
	 * @return Payment
	 */
	public function pay(array $data)
	{
		$payment = $this->order->payments()->make($data);

		$payment->order_customer_id = $this->order->customer->id;
		$payment->order_total = $this->order->total;
		$payment->payment_total = $this->order->remaining;
		$payment->remaining = $payment->payment_total - $payment->pay;
		$payment->cs_user_id = $this->cs->id;
		$payment->cs_name = $this->cs->name;

		$this->setPaymentType($payment);

		$payment->save();

		$this->updateOrderPayment($payment);
    	$this->createOrderHistory($payment->type);

		return $payment;
	}

	/**
	 * generate type
	 *
	 * @param Payment $payment
	 * @return string
	 */
	protected function setPaymentType(Payment &$payment)
	{
		$payment->type = 'rest_payment';

		if ($this->order->paid_off === 0) {
			$payment->type = 'down_payment';
		}

		if ($this->isPaid($payment->pay)) {
			$payment->type = 'settlement';
		}
	}

	/**
     * get total paid or except given payment pay
     *
     * @param float $pay
     * @param Payment $payment
     * @return float
     */
    protected function getTotalPaid(float $pay, Payment $payment = null)
    {
        $paidOff = $this->order->paid_off;

        if (! is_null($payment)) {
        	$paidOff -= $payment->pay;
        }

        return $paidOff + $pay;
    }

    /**
     * check is paid by last pay
     *
     * @param float $pay
     * @return bool
     */
    protected function isPaid(float $pay)
    {
    	return $this->getTotalPaid($pay) == $this->order->total;
    }

    /**
     * check is over payment
     *
     * @param float $pay
     * @param Payment $payment
     * @return bool
     */
    public function isOverpayment(float $pay, Payment $payment = null)
    {
		return $this->getTotalPaid($pay, $payment) > $this->order->total;
    }

    /**
     * update order payment
     *
     * @param Payment $payment
     * @return void
     */
    protected function updateOrderPayment(Payment $payment)
    {
    	$this->order->update([
    		'paid_off' => $totalPaid = $this->getTotalPaid($payment->pay),
    		'remaining' => $payment->remaining,
    		'paid' => $this->isPaid($payment->pay)
    	]);
    }

    /**
     * reset order payment
     *
     * @return void
     */
    protected function resetOrderPayment()
    {
    	$this->order->update([
    		'paid_off' => 0,
    		'remaining' => $this->order->item_total,
    		'paid' => 0
    	]);
    }

    /**
     * reset all payment becouse order change
     *
     * @return void
     */
    public function resetAllPayment()
    {
    	$this->resetOrderPayment();
    	$payments = $this->order->payments()->orderBy('id', 'DESC')->get();

    	foreach ($payments as $payment) {
    		$payment->order_total = $this->order->total;
			$payment->payment_total = $this->order->remaining;
			$payment->remaining = $payment->payment_total - $payment->pay;
	
			$this->setPaymentType($payment);

			$payment->save();

    		$this->updateOrderPayment($payment);
    	}
    }

    /**
     * order history
     *
     * @param string $status
     * @return void
     */
    protected function createOrderHistory($status)
    {
    	$this->order->histories()->create(['type' => 'payment', 'label' => $status]);
    }

}