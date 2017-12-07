<?php

namespace Denmasyarikin\Sales\Order;

use App\Model;
use Denmasyarikin\Sales\Order\Contracts\Adjustment;

class OrderAdjustment extends Model implements Adjustment
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sales_order_adjustments';

    /**
     * Get the order record associated with the OrderItem.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * get adjustmentable
     *
     * @return Adjustmentable
     */
    public function getAdjustmentable()
    {
        return $this->order;
    }
}
