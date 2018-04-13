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
     * The relationships that should be touched on save.
     *
     * @var array
     */
    protected $touches = ['order'];

    /**
     * cacheAdjustments.
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
        return $this->belongsTo('Modules\Unit\Unit')->withTrashed();
    }

    /**
     * Get the dimensionUnit record associated with the OrderItem.
     */
    public function dimensionUnit()
    {
        return $this->belongsTo('Modules\Unit\Unit', 'dimension_unit_id')->withTrashed();
    }

    /**
     * Get the adjustments record associated with the OrderItem.
     */
    public function adjustments()
    {
        return $this->hasMany(OrderItemAdjustment::class);
    }

    /**
     * get adjustments.
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
     * update order total.
     */
    public function updateTotal()
    {
        $this->update([
            'total' => $this->unit_total + $this->adjustment_total,
        ]);
    }

    /**
     * get discount.
     *
     * @return OrderItemAdjustment
     */
    public function getDiscount()
    {
        return $this->adjustments()->whereType('discount')->first();
    }

    /**
     * get markup.
     *
     * @return OrderItemAdjustment
     */
    public function getMarkup()
    {
        return $this->adjustments()->whereType('markup')->first();
    }

    /**
     * get voucher.
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
        return ($markup = $this->getMarkup())
            ? (null === $markup->adjustment_value ? $markup->adjustment_total : $markup->adjustment_value)
            : 0;
    }

    /**
     * Get MarkupType.
     *
     * @return string
     */
    public function getMarkupTypeAttribute()
    {
        return ($markup = $this->getMarkup())
            ? (null === $markup->adjustment_value ? 'amount' : 'percentage')
            : 'percentage';
    }

    /**
     * Get Discount.
     *
     * @return string
     */
    public function getDiscountAttribute()
    {
        return ($discount = $this->getDiscount())
            ? (null === $discount->adjustment_value ? $discount->adjustment_total : $discount->adjustment_value)
            : 0;
    }

    /**
     * Get DiscountType.
     *
     * @return string
     */
    public function getDiscountTypeAttribute()
    {
        return ($discount = $this->getDiscount())
            ? (null === $discount->adjustment_value ? 'amount' : 'percentage')
            : 'percentage';
    }

    /**
     * Get Voucher.
     *
     * @param string $value
     *
     * @return string
     */
    public function getVoucherAttribute($value)
    {
        return ($voucher = $this->getVoucher()) ? $voucher->adjustment_value : '';
    }

    /**
     * check is product.
     *
     * @return bool
     */
    public function isProduct()
    {
        if ('product' === $this->type) {
            return 'product' === $this->type_as;
        }

        return false;
    }

    /**
     * check is product process.
     *
     * @return bool
     */
    public function isProductProcess()
    {
        if ('product' === $this->type) {
            return 'product' !== $this->type_as;
        }

        return false;
    }
}
