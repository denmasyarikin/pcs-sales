<?php

namespace Denmasyarikin\Sales\Order;

use App\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderHistory extends Model
{
    use SoftDeletes;

    /**
     * The relationships that should be touched on save.
     *
     * @var array
     */
    protected $touches = ['order'];

    /**
     * mapping.
     *
     * @var array
     */
    protected $mapping = [
        'draft' => 'Kalkulasi Pesanan',
        'created' => 'Pesanan Dibuat',
        'processing' => 'Pesanan Diproses',
        'finished' => 'Pesanan Selesai',
        'taken' => 'Pesanan Diambil',
        'closed' => 'Pesanan Diarsipkan',
        'canceled' => 'Pesanan Dibatalkan',
        'change_due_date' => 'Ubah Tempo Pembayaran',
        'change_estimated_finish_date' => 'Ubah Perkiraan Selesai',
        // payment
        'down_payment' => 'Bayar Uang Muka',
        'rest_payment' => 'Pembayaran Sisa',
        'settlement' => 'Pembayaran Lunas',
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sales_order_histories';

    /**
     * Get the order record associated with the OrderItem.
     */
    public function order()
    {
        return $this->belongsTo(Order::class)->withTrashed();
    }

    /**
     * Get Label.
     *
     * @param string $value
     *
     * @return string
     */
    public function getLabelAttribute($value)
    {
        if (array_key_exists($value, $this->mapping)) {
            return $this->mapping[$value];
        }

        return $value;
    }

    /**
     * Get Data.
     *
     * @param  string  $value
     * @return string
     */
    public function getDataAttribute($value)
    {
        return json_decode($value);
    }

    /**
     * Set Data.
     *
     * @param  string  $value
     */
    public function setDataAttribute($value)
    {
        $this->attributes['data'] = json_encode($value);
    }

    /**
     * The "booting" method of the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($history) {
            $history->actor = Auth::user()->name;
        });
    }
}
