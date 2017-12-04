<?php

namespace Denmasyarikin\Sales\Order;

use App\Model;
use Denmasyarikin\Sales\Customer\Customer;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderCustomer extends Model
{
    use SoftDeletes;

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
        return $this->hasOne(Customer::class);
    }

    /**
     * Get the order record associated with the OrderItem.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
