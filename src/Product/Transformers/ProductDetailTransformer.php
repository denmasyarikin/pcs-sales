<?php

namespace Denmasyarikin\Sales\Product\Transformers;

use App\Http\Transformers\Detail;
use Illuminate\Database\Eloquent\Model;
use Modules\Unit\Transformers\UnitListDetailTransformer;
use Modules\Workspace\Transformers\WorkspaceListTransformer;

class ProductDetailTransformer extends Detail
{
    /**
     * get data.
     *
     * @param Model $model
     *
     * @return array
     */
    protected function getData(Model $model)
    {
        return [
            'id' => $model->id,
            'name' => $model->name,
            'description' => $model->description,
            'product_category_id' => $model->product_category_id,
            'product_category' => (new ProductCategoryDetailTransformer($model->productCategory))->toArray(),
            'image' => $model->image,
            'status' => $model->status,
            'unit_id' => $model->unit_id,
            'unit' => (new UnitListDetailTransformer($model->unit))->toArray(),
            'min_order' => $model->min_order,
            'order_multiples' => $model->order_multiples,
            'base_price' => $model->base_price,
            'per_unit_price' => $model->per_unit_price,
            'process_count' => $model->processes->count(),
            'workspace_ids' => $model->workspaces->pluck('id'),
            'workspaces' => (new WorkspaceListTransformer($model->workspaces))->toArray(),
            'created_at' => $model->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $model->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
