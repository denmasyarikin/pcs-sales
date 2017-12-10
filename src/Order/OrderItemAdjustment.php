<?php

namespace Denmasyarikin\Sales\Order;

use App\Model;
use Denmasyarikin\Sales\Order\Contracts\Adjustment;

class OrderItemAdjustment extends Model implements Adjustment
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sales_order_item_adjustments';

    /**
     * Get the orderItem record associated with the OrderItem.
     */
    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class);
    }

    /**
     * get adjustmentable.
     *
     * @return Adjustmentable
     */
    public function getAdjustmentable()
    {
        return $this->orderItem;
    }
}
