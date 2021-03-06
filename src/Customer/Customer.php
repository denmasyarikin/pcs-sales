<?php

namespace Denmasyarikin\Sales\Customer;

use App\Model;
use Modules\Chanel\Chanel;
use Denmasyarikin\Sales\Order\Order;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sales_customers';

    /**
     * Get the chanel record associated with the Customer.
     */
    public function chanel()
    {
        return $this->belongsTo('Modules\Chanel\Chanel')->withTrashed();
    }

    /**
     * Get the orders record associated with the Customer.
     */
    public function orders()
    {
        return $this->belongsToMany(Order::class, 'sales_order_customers');
    }

    /**
     * Get Code.
     *
     * @return string
     */
    public function getCodeAttribute()
    {
        return $this->chanel->code.str_pad($this->id, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Get DueDateType.
     *
     * @return string
     */
    public function getDueDateTypeAttribute($value)
    {
        return is_null($this->due_date_day_count) ? 'COD' : 'Tempo';
    }

    /**
     * check id given string code.
     *
     * @param string $code
     *
     * @return bool
     */
    public static function isCode($code)
    {
        return 6 === strlen($code) and
            Chanel::isCode(substr($code, 0, 3)) and
            is_numeric(substr($code, 3, 3)); // from getCodeAttribute
    }

    /**
     * get id from code.
     *
     * @param string $code
     *
     * @return int
     */
    public static function getIdFromCode($code)
    {
        return [
            'chanel_code' => substr($code, 0, 3),
            'id' => intval(substr($code, 3, 3)),
        ];
    }
}
