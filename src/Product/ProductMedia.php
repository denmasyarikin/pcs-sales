<?php

namespace Denmasyarikin\Sales\Product;

use App\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductMedia extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sales_product_medias';

    /**
     * Get the product record associated with the Product.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
