<?php

namespace Denmasyarikin\Sales\Product;

use App\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductOpration extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sales_product_oprations';

    /**
     * Get the productConfiguration record associated with the Product.
     */
    public function productConfiguration()
    {
        return $this->belongsTo(ProductConfiguration::class)->withTrashed();
    }

    /**
     * Get the productProcess record associated with the Product.
     */
    public function productProcess()
    {
        return $this->belongsTo(ProductProcess::class)->withTrashed();
    }
}
