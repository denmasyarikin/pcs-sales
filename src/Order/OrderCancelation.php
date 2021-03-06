<?php

namespace Denmasyarikin\Sales\Order;

use App\Model;

class OrderCancelation extends Model
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
    protected $table = 'sales_order_cancelations';

    /**
     * Get the order record associated with the OrderItem.
     */
    public function order()
    {
        return $this->belongsTo(Order::class)->withTrashed();
    }
}
