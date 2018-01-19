<?php

namespace Denmasyarikin\Sales\Product;

use App\Model;
use App\Manager\Facades\Setting;
use Illuminate\Support\Facades\URL;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes, ProcessCounterTrait;

    /**
     * cache processes.
     *
     * @var Collection
     */
    protected $cacheProcesses;

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
        return $this->belongsTo('Modules\Unit\Unit')->withTrashed();
    }

    /**
     * Get the group record associated with the ProductGroup.
     */
    public function group()
    {
        return $this->belongsTo(ProductGroup::class, 'product_group_id')->withTrashed();
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
     * create process.
     *
     * @param array $process
     *
     * @return ProductProcess
     */
    public function createProcess(array $process)
    {
        return $productProcess = $this->processes()->create($process);
    }

    /**
     * update product price.
     */
    public function updateProductPrice()
    {
        $this->base_price = 0;

        foreach ($this->getProcesses()->whereStrict('parent_id', null) as $process) {
            $this->base_price += $process->base_price * $process->quantity;
            $this->per_unit_price = ceil($this->base_price / $this->min_order);
        }

        $this->save();
    }

    /**
     * get processes.
     *
     * @return Collection
     */
    public function getProcesses()
    {
        if ($this->cacheProcesses) {
            return $this->cacheProcesses;
        }

        return $this->cacheProcesses = $this->processes;
    }
}
