<?php

namespace Denmasyarikin\Sales\Order;

use App\Model;
use Illuminate\Support\Collection;
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

    /**
     * Get the adjustments record associated with the OrderItem.
     */
    public function adjustments()
    {
        return $this->hasMany(OrderAdjustment::class);
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
    protected function getPrimaryItems()
    {
        $items = new Collection();
        $items->merge($this->itemProduct);
        $items->merge($this->itemGood);
        $items->merge($this->itemService);
        $items->merge($this->itemManual);

        return $item;
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
