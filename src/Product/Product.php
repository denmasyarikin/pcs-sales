<?php

namespace Denmasyarikin\Sales\Product;

use App\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sales_products';

    /**
     * Get the unit record associated with the Product.
     */
    public function unit()
    {
        return $this->belongsTo('Modules\Unit\Unit');
    }

    /**
     * Get the groups record associated with the ProductGroup.
     */
    public function groups()
    {
        return $this->belongsToMany(ProductGroup::class, 'sales_product_group_products');
    }
}
