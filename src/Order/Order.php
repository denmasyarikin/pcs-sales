<?php

namespace Denmasyarikin\Sales\Order;

use App\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sales_orders';

    /**
     * Get the customer record associated with the Order.
     */
    public function customer()
    {
        return $this->hasOne(OrderCustomer::class);
    }

    /**
     * Get the items record associated with the Order.
     */
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get the histories record associated with the Order.
     */
    public function histories()
    {
        return $this->hasMany(OrderHistory::class);
    }
}