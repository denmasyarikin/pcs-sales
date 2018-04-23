<?php

namespace Denmasyarikin\Sales\Order;

use App\Model;
use Modules\Chanel\Chanel;
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
     * Get the adjustments record associated with the OrderItem.
     */
    public function attachments()
    {
        return $this->hasMany(OrderAttachment::class);
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

        return $this->cacheAdjustments = $this->adjustments()->orderBy('priority', 'ASC')->get();
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
        $total = $this->item_total + $this->adjustment_total;

        $this->update([
            'total' => $total,
            'remaining' => $total - $this->paid_off,
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

    /**
     * over due date
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOverDueDate($query, $date)
    {
        return $query->whereDate('due_date', '<', $date)
                        ->whereIn('status', ['created', 'processing', 'finished'])
                        ->where('paid', false);
    }

    /**
     * over estimated
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOverEstimated($query, $date)
    {
        return $query->whereDate('estimated_finish_date', '<', $date)->whereIn('status', ['draft', 'created', 'processing']);
    }

    /**
     * Get Code.
     *
     * @param  string  $value
     * @return string
     */
    public function getCodeAttribute($value)
    {
        $chanel = $this->chanel->code;
        $cs = str_pad($this->cs_user_id, 2, '0', STR_PAD_LEFT);
        $order = str_pad($this->id, 5, '0', STR_PAD_LEFT);

        return $chanel . $cs . $order;
    }
    
    /**
     * check id given string code
     *
     * @param string $code
     * @return bool
     */
    public static function isCode($code)
    {
        // better way use regex in next time
        return strlen($code) === 10 AND
            Chanel::isCode(substr($code, 0, 3)) AND
            is_numeric(substr($code, 3, 2)) AND
            is_numeric(substr($code, 5, 5)); // from getCodeAttribute
    }

    /**
     * get id from code.
     *
     * @param string $code
     * @return array
     */
    public static function getIdFromCode($code)
    {
        return [
            'chanel_code' => substr($code, 0, 3),
            'cs_user_id' => intval(substr($code, 3, 2)),
            'id' => intval(substr($code, 5, 5))
        ];
    }
}
