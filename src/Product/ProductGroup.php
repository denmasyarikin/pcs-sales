<?php

namespace Denmasyarikin\Sales\Product;

use App\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductGroup extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sales_product_groups';

    /**
     * Get the parent record associated with the ProductGroup.
     */
    public function parent()
    {
        return $this->belongsTo(static::class, 'parent_id');
    }

    /**
     * Get the children record associated with the ProductGroup.
     */
    public function children()
    {
        return $this->hasMany(static::class, 'parent_id');
    }

    /**
     * Get the products record associated with the ProductGroup.
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'sales_product_group_products')
                    ->withTimestamps();
    }
}
