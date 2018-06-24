<?php

namespace Denmasyarikin\Sales\Payment;

use App\Model;
use Denmasyarikin\Sales\Order\Order;
use Denmasyarikin\Sales\Order\OrderCustomer;

class Payment extends Model
{
    /**
     * The relationships that should be touched on save.
     *
     * @var array
     */
    protected $touches = ['order'];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sales_payments';

    /**
     * Get the order record associated with the Payment.
     */
    public function order()
    {
        return $this->belongsTo(Order::class)->withTrashed();
    }

    /**
     * Get the customer record associated with the Payment.
     */
    public function customer()
    {
        return $this->belongsTo(OrderCustomer::class, 'order_customer_id');
    }
}
