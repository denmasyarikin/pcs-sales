<?php

namespace Denmasyarikin\Sales\Product\Transformers;

use App\Http\Transformers\Detail;
use Illuminate\Database\Eloquent\Model;
use Modules\Unit\Transformers\UnitListDetailTransformer;

class ProductListDetailTransformer extends Detail
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
        $group = $model->group;

        return [
            'id' => $model->id,
            'name' => $model->name,
            'product_category' => (new ProductCategoryDetailTransformer($model->productCategory))->toArray(),
            'product_category_id' => $model->product_category_id,
            'description' => $model->description,
            'image' => $model->image,
            'unit_id' => $model->unit_id,
            'unit' => (new UnitListDetailTransformer($model->unit))->toArray(),
            'min_order' => (int) $model->min_order,
            'order_multiples' => (int) $model->order_multiples,
            'base_price' => $model->base_price ?: 0,
            'per_unit_price' => $model->per_unit_price ?: 0,
            'process_count' => $model->process_count,
            'workspace_ids' => $model->workspaces->pluck('id'),
            'workspaces' => (new WorkspaceListTransformer($model->workspaces))->toArray(),
            'status' => $model->status ?: 'draft',
            'created_at' => $model->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $model->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
