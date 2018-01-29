<?php

namespace Denmasyarikin\Sales\Product\Transformers;

use App\Http\Transformers\Detail;
use Illuminate\Database\Eloquent\Model;
use Modules\Unit\Transformers\UnitDetailTransformer;

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
            'group_name' => $group ? $group->name : null,
            'description' => $model->description,
            'image' => $model->image,
            'unit' => (new UnitDetailTransformer($model->unit, ['id', 'name', 'specific', 'formatted']))->toArray(),
            'min_order' => (int) $model->min_order,
            'base_price' => $model->base_price ?: 0,
            'per_unit_price' => $model->per_unit_price ?: 0,
            'process_count' => $model->process_count,
            'customizable' => (bool) $model->customizable,
            'status' => $model->status ?: 'draft',
            'created_at' => $model->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $model->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
