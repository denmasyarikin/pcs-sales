<?php

namespace Denmasyarikin\Sales\Product;

use App\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductGroup extends Model
{
    use SoftDeletes;

    /**
     * paths
     *
     * @var array
     */
    protected $paths;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sales_product_groups';

    /**
     * Get the products record associated with the ProductGroup.
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
