<?php

namespace Denmasyarikin\Sales\Order;

use App\Model;
use Denmasyarikin\Sales\Customer\Customer;

class OrderCustomer extends Model
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
    protected $table = 'sales_order_customers';

    /**
     * Get the customer record associated with the OrderCustomer.
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class)->withTrashed();
    }

    /**
     * Get the order record associated with the OrderItem.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
