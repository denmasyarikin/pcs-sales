<?php

namespace Denmasyarikin\Sales\Product;

use App\Model;
use App\Manager\Facades\Setting;
use Illuminate\Support\Facades\URL;
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
        return $this->belongsToMany(ProductGroup::class, 'sales_product_group_products')
                    ->withTimestamps();
    }

    /**
     * Get the processes record associated with the Product.
     */
    public function processes()
    {
        return $this->hasMany(ProductProcess::class);
    }

    /**
     * Get the medias record associated with the Product.
     */
    public function medias()
    {
        return $this->hasMany(ProductMedia::class);
    }

    /**
     * Get Image.
     *
     * @return string
     */
    public function getImageAttribute()
    {
        $default = URL::to(Setting::get('system.sales.product.default_image'));

        if (0 === count($medias = $this->medias)) {
            return $default;
        }

        $primary = $medias->where('primary', true)->first();

        if (is_null($primary)) {
            $primary = $medias->first();
        }

        return URL::to($primary->content);
    }
}
