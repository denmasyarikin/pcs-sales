<?php

namespace Denmasyarikin\Sales\Order;

use App\Model;
use Illuminate\Support\Collection;
use Denmasyarikin\Sales\Payment\Payment;
use Illuminate\Database\Eloquent\SoftDeletes;
use Denmasyarikin\Sales\Order\Contracts\Taxable;
use Denmasyarikin\Sales\Order\Contracts\Voucherable;
use Denmasyarikin\Sales\Order\Contracts\Discountable;

class Order extends Model implements Taxable, Voucherable, Discountable
{
    use SoftDeletes, ItemCounterTrait;

    /**
     * cacheItems.
     *
     * @var Collection
     */
    protected $cacheItems;

    /**
     * cacheAdjustments.
     *
     * @var Collection
     */
    protected $cacheAdjustments;

    /**
     * cachePayments.
     *
     * @var Collection
     */
    protected $cachePayments;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sales_orders';

    /**
     * Get the chanel record associated with the Order.
     */
    public function chanel()
    {
        return $this->belongsTo('Modules\Chanel\Chanel')->withTrashed();
    }

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

    /**
     * Get the adjustments record associated with the OrderItem.
     */
    public function adjustments()
    {
        return $this->hasMany(OrderAdjustment::class);
    }

    /**
     * Get the cancelation record associated with the Order.
     */
    public function cancelation()
    {
        return $this->hasOne(OrderCancelation::class);
    }

    /**
     * Get the payments record associated with the Order.
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * get items.
     *
     * @return Collection
     */
    public function getItems()
    {
        if ($this->cacheItems) {
            return $this->cacheItems;
        }

        return $this->cacheItems = $this->items;
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
     * get payments.
     *
     * @return Collection
     */
    public function getPayments()
    {
        if ($this->cachePayments) {
            return $this->cachePayments;
        }

        return $this->cachePayments = $this->payments;
    }

    /**
     * update order total.
     */
    public function updateTotal()
    {
        $this->update([
            'total' => $this->item_total + $this->adjustment_total,
        ]);
    }

    /**
     * get primary item.
     *
     * @return Collection
     */
    public function getPrimaryItems()
    {
        return $this->itemProduct
            ->concat($this->itemGood)
            ->concat($this->itemService)
            ->concat($this->itemManual);
    }

    /**
     * get discount.
     *
     * @return OrderAdjustment
     */
    public function getDiscount()
    {
        return $this->adjustments()->whereType('discount')->first();
    }

    /**
     * get voucher.
     *
     * @return OrderAdjustment
     */
    public function getVoucher()
    {
        return $this->adjustments()->whereType('voucher')->first();
    }

    /**
     * get tax.
     *
     * @return OrderAdjustment
     */
    public function getTax()
    {
        return $this->adjustments()->whereType('tax')->first();
    }
}
