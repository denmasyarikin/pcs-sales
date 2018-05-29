<?php

namespace Denmasyarikin\Sales\Product;

use App\Model;
use Modules\Workspace\WorkspaceRelation;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductCategory extends Model
{
    use SoftDeletes, WorkspaceRelation;

    /**
     * paths.
     *
     * @var array
     */
    protected $paths;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sales_product_categories';

    /**
     * Get the products record associated with the ProductGroup.
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Get the parent record associated with the GoodCategory.
     */
    public function parent()
    {
        return $this->belongsTo(static::class);
    }

    /**
     * Get the children record associated with the GoodCategory.
     */
    public function children()
    {
        return $this->hasMany(static::class, 'parent_id');
    }

    /**
     * Get the workspaces record associated with the Good.
     */
    public function workspaces()
    {
        return $this->belongsToMany('Modules\Workspace\Workspace', 'sales_product_category_workspaces')->whereStatus('active')->withTimestamps();
    }
}
