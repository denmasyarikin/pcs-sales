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
        return $this->belongsTo(Product::class)->withTrashed();
    }

    /**
     * Get the unit record associated with the Product.
     */
    public function unit()
    {
        return $this->belongsTo('Modules\Unit\Unit')->withTrashed();
    }

    /**
     * Get all of the owning commentable models.
     */
    public function reference()
    {
        //there are only 4 possibilities
        // - Good
        // - GoodVariant
        // - Service
        // - ServiceOption

        return $this->morphTo('reference');
    }

    /**
     * Get ReferenceConfigurations.
     *
     * @param  string  $value
     * @return string
     */
    public function getReferenceConfigurationsAttribute($value)
    {
        return json_decode($value);
    }

    /**
     * Set ReferenceConfigurations.
     *
     * @param  string  $value
     * @return string
     */
    public function setReferenceConfigurationsAttribute($value)
    {
        $this->attributes['reference_configurations'] = json_encode($value);
    }
}
