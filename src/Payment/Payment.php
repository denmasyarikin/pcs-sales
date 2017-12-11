<?php

namespace Denmasyarikin\Sales\Payment;

use App\Model;
use Denmasyarikin\Sales\Bank\Bank;
use Denmasyarikin\Sales\Order\Order;
use Denmasyarikin\Sales\Order\OrderCustomer;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{

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
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the customer record associated with the Payment.
     */
    public function customer()
    {
    	return $this->belongsTo(OrderCustomer::class, 'order_customer_id');
    }

    /**
     * Get the bank record associated with the Payment.
     */
    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }
}