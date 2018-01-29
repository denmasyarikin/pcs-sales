<?php

namespace Denmasyarikin\Sales\Customer;

use App\Model;
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
}
