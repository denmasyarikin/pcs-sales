<?php

namespace Denmasyarikin\Sales\Payment;

use App\Manager\Facades\Money;
use Illuminate\Support\Facades\Auth;
use Denmasyarikin\Sales\Order\Order;

class Factory
{
    /**
     * customer service.
     *
     * @var User
     */
    protected $cs;

    /**
     * order.
     *
     * @var Order
     */
    protected $order;

    /**
     * payments.
     *
     * @var Collection
     */
    protected $payments;

    /**
     * Create a new Constructor instance.
     *
     * @param Order $order
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
        $this->cs = Auth::user();
        $this->payments = $order->payments;
    }

    /**
     * pay order.
     *
     * @param array $data
     *
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
        $this->createOrderHistory($payment);

        return $payment;
    }

    /**
     * generate type.
     *
     * @param Payment $payment
     *
     * @return string
     */
    protected function setPaymentType(Payment &$payment)
    {
        $payment->type = 'rest_payment';

        // use two equality is important
        if (0 == $this->order->paid_off) {
            $payment->type = 'down_payment';
        }

        if ($this->isPaid($payment->pay)) {
            $payment->type = 'settlement';
        }
    }

    /**
     * get total paid or except given payment pay.
     *
     * @param float   $pay
     * @param Payment $payment
     *
     * @return float
     */
    protected function getTotalPaid(float $pay, Payment $payment = null)
    {
        $paidOff = $this->order->paid_off;

        if (!is_null($payment)) {
            $paidOff -= $payment->pay;
        }

        return $paidOff + $pay;
    }

    /**
     * check is paid by last pay.
     *
     * @param float $pay
     *
     * @return bool
     */
    protected function isPaid(float $pay)
    {
        return $this->getTotalPaid($pay) == $this->order->total;
    }

    /**
     * check is over payment.
     *
     * @param float   $pay
     * @param Payment $payment
     *
     * @return bool
     */
    public function isOverpayment(float $pay, Payment $payment = null)
    {
        return $this->getTotalPaid($pay, $payment) > $this->order->total;
    }

    /**
     * update order payment.
     *
     * @param Payment $payment
     */
    protected function updateOrderPayment(Payment $payment)
    {
        $this->order->update([
            'paid_off' => $this->getTotalPaid($payment->pay),
            'remaining' => $payment->remaining,
            'paid' => $this->isPaid($payment->pay),
        ]);
    }

    /**
     * reset order payment.
     */
    protected function resetOrderPayment()
    {
        $this->order->update([
            'paid_off' => 0,
            'remaining' => $this->order->item_total,
            'paid' => 0,
        ]);
    }

    /**
     * reset all payment becouse order change.
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
     * order history.
     *
     * @param Payment $payment
     */
    protected function createOrderHistory(Payment $payment)
    {
        $this->order->histories()->create([
            'type' => 'payment',
            'label' => $payment->type,
            'data' => [
                'method' => $payment->payment_method,
                'payment_total' => Money::format($payment->payment_total),
                'pay' => Money::format($payment->pay),
                'remaining' => Money::format($payment->remaining),
            ],
        ]);
    }
}
