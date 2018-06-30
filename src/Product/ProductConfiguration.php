<?php

namespace Denmasyarikin\Sales\Product;

use App\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductConfiguration extends Model
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
    protected $table = 'sales_product_configurations';

    /**
     * Get the product record associated with the Product.
     */
    public function product()
    {
        return $this->belongsTo(Product::class)->withTrashed();
    }

    /**
     * Get the productOprations record associated with the ProductOpration.
     */
    public function productOprations()
    {
        return $this->hasMany(ProductOpration::class);
    }

    /**
     * Get the productProcesses record associated with the ProductProcess.
     */
    public function productProcesses()
    {
        return $this->belongsToMany(ProductProcess::class, 'sales_product_oprations')
            ->withPivot('condition', 'condition_value', 'opration', 'opration_value')
            ->withTimestamps();
    }

    /**
     * Get Configuration.
     *
     * @param string $value
     *
     * @return string
     */
    public function getConfigurationAttribute($value)
    {
        return json_decode($value);
    }

    /**
     * Set Configuration.
     *
     * @param string $value
     *
     * @return string
     */
    public function setConfigurationAttribute($value)
    {
        $this->attributes['configuration'] = json_encode($value);
    }
}
