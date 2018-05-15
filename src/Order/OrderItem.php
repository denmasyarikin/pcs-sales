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
     * Get the adjustments record associated with the OrderItem.
     */
    public function adjustments()
    {
        return $this->hasMany(OrderItemAdjustment::class);
    }

    /**
     * Get all of the owning commentable models.
     */
    public function reference()
    {
        //there are only 4 possibilities
        // - Product
        // - ProductProcess
        // - Good
        // - Service

        return $this->morphTo('reference');
    }

    /**
     * Get the parent record associated with the OrderItem.
     */
    public function parent()
    {
        return $this->belongsTo(static::class, 'parent_id');
    }

    /**
     * Get the children record associated with the OrderItem.
     */
    public function children()
    {
        return $this->hasMany(static::class, 'parent_id');
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
     * check is good.
     *
     * @return bool
     */
    public function isGood()
    {
        return 'good' === $this->type
            AND 'good' === $this->type_as;
    }
    /**
     * check is service.
     *
     * @return bool
     */
    public function isService()
    {
        return 'service' === $this->type
            AND 'service' === $this->type_as;
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

    /**
     * Set ReferenceConfiguration.
     *
     * @param  string  $value
     * @return string
     */
    public function setReferenceConfigurationAttribute($value)
    {
        if ($value !== null) {
            $this->attributes['reference_configuration'] = json_encode($value);
        }
    }

    /**
     * Get ReferenceConfiguration.
     *
     * @param  string  $value
     * @return string
     */
    public function getReferenceConfigurationAttribute($value)
    {
        if ($value !== null) {
            return json_decode($value);
        }
    }
}
