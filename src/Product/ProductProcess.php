<?php

namespace Denmasyarikin\Sales\Product;

use App\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductProcess extends Model
{
    use SoftDeletes;

    /**
     * The relationships that should be touched on save.
     *
     * @var array
     */
    protected $touches = ['product'];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sales_product_processes';

    /**
     * Get the product record associated with the Product.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the unit record associated with the Product.
     */
    public function unit()
    {
        return $this->belongsTo('Modules\Unit\Unit');
    }

    /**
     * Get all of the owning commentable models.
     */
    public function reference()
    {
        //there are only 2 possibilities
        // - Good
        // - Service

        return $this->morphTo('reference');
    }

    /**
     * Get the dimensionUnit record associated with the OrderItem.
     */
    public function dimensionUnit()
    {
        return $this->belongsTo('Modules\Unit\Unit', 'dimension_unit_id')->withTrashed();
    }

    /**
     * The "booting" method of the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::saved(function ($process) {
            $product = $process->product;
            $product->updateProductPrice();
        });

        static::deleted(function ($process) {
            $product = $process->product;
            $product->updateProductPrice();
        });
    }
}
