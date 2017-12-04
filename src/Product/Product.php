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

    /**
     * create process
     *
     * @param array $process
     * @return ProductProcess
     */
    public function createProcess(array $process)
    {
        return $productProcess = $this->processes()->create($process);
    }

    /**
     * update product price
     *
     * @return void
     */
    public function updateProductPrice()
    {
        $this->resetProcessCount();

        foreach ($this->processes()->whereNull('parent_id')->get() as $process) {
            $this->base_price += $process->base_price * $process->quantity;
            $this->per_unit_price = ceil($this->base_price / $this->min_order);
            $this->{'process_'.$process->type.'_count'} += 1;
        }

        $this->save();
    }

    /**
     * reset process count
     *
     * @return void
     */
    protected function resetProcessCount()
    {
        $this->base_price = 0;
        $this->per_unit_price = 0;
        $this->process_good_count = 0;
        $this->process_service_count = 0;
        $this->process_manual_count = 0;
    }
}
