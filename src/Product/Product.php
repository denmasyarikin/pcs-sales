<?php

namespace Denmasyarikin\Sales\Product;

use App\Model;
use Modules\Workspace\WorkspaceRelation;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes, WorkspaceRelation;

    /**
     * cache processes.
     *
     * @var Collection
     */
    protected $cacheProcesses;

    /**
     * cache configurations.
     *
     * @var Collection
     */
    protected $cacheConfigurations;

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
     * Get the productCategory record associated with the ProductGroup.
     */
    public function productCategory()
    {
        return $this->belongsTo(ProductCategory::class)->withTrashed();
    }

    /**
     * Get the processes record associated with the Product.
     */
    public function processes()
    {
        return $this->hasMany(ProductProcess::class);
    }

    /**
     * Get the configurations record associated with the Product.
     */
    public function configurations()
    {
        return $this->hasMany(ProductConfiguration::class);
    }

    /**
     * Get the medias record associated with the Product.
     */
    public function medias()
    {
        return $this->hasMany(ProductMedia::class);
    }

    /**
     * Get the workspaces record associated with the Good.
     */
    public function workspaces()
    {
        return $this->belongsToMany('Modules\Workspace\Workspace', 'sales_product_workspaces')->whereStatus('active')->withTimestamps();
    }

    /**
     * Get Image.
     *
     * @return string
     */
    public function getImageAttribute()
    {
        if (0 === count($medias = $this->medias)) {
            return null;
        }

        $primary = $medias->where('primary', true)->first();

        if (is_null($primary)) {
            $primary = $medias->first();
        }

        return $primary->content;
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
     * get processes.
     *
     * @return Collection
     */
    public function getProcesses()
    {
        if ($this->cacheProcesses) {
            return $this->cacheProcesses;
        }

        return $this->cacheProcesses = $this->processes()->orderBy('created_at', 'ASC')->get();
    }

    /**
     * get configurations.
     *
     * @return Collection
     */
    public function getConfigurations()
    {
        if ($this->cacheConfigurations) {
            return $this->cacheConfigurations;
        }

        return $this->cacheConfigurations = $this->configurations()->orderBy('created_at', 'ASC')->get();
    }
}
