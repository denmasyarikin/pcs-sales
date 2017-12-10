<?php

namespace Denmasyarikin\Sales\Order;

use App\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Denmasyarikin\Sales\Order\Contracts\Markupable;
use Denmasyarikin\Sales\Order\Contracts\Discountable;
use Denmasyarikin\Sales\Order\Contracts\Voucherable;

class OrderItem extends Model implements Markupable, Discountable, Voucherable
{
    use SoftDeletes;

    /**
     * cacheAdjustments
     *
     * @var Collection
     */
    protected $cacheAdjustments;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sales_order_items';

    /**
     * Get the order record associated with the OrderItem.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the unit record associated with the OrderItem.
     */
    public function unit()
    {
        return $this->belongsTo('Modules\Unit\Unit');
    }

    /**
     * Get the adjustments record associated with the OrderItem.
     */
    public function adjustments()
    {
        return $this->hasMany(OrderItemAdjustment::class);
    }

    /**
     * get adjustments
     *
     * @return Collection
     */
    public function getAdjustments()
    {
        if ($this->cacheAdjustments) {
            return $this->cacheAdjustments;
        }

        return $this->cacheAdjustments = $this->adjustments;
    }

    /**
     * update order total
     *
     * @return void
     */
    public function updateTotal()
    {
        $this->update([
            'total' => $this->unit_total + $this->adjustment_total
        ]);
    }

    /**
     * get discount
     *
     * @return OrderItemAdjustment
     */
    public function getDiscount()
    {
        return $this->adjustments()->whereType('discount')->first();
    }

    /**
     * get markup
     *
     * @return OrderItemAdjustment
     */
    public function getMarkup()
    {
        return $this->adjustments()->whereType('markup')->first();
    }

    /**
     * get voucher
     *
     * @return OrderItemAdjustment
     */
    public function getVoucher()
    {
        return $this->adjustments()->whereType('voucher')->first();
    }

    /**
     * Get Markup.
     *
     * @return string
     */
    public function getMarkupAttribute()
    {
        return ($markup = $this->getMarkup()) ? $markup->adjustment_value : 0;
    }

    /**
     * Get Discount.
     *
     * @return string
     */
    public function getDiscountAttribute()
    {
        return ($discount = $this->getDiscount()) ? $discount->adjustment_value : 0;
    }

    /**
     * Get Voucher.
     *
     * @param  string  $value
     * @return string
     */
    public function getVoucherAttribute($value)
    {
        return ($voucher = $this->getVoucher()) ? $voucher->adjustment_value : 0;
    }
}
