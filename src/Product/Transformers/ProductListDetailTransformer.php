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
        return [
            'id' => $model->id,
            'name' => $model->name,
            'description' => $model->description,
            'unit' => (new UnitDetailTransformer($model->unit, ['id', 'name']))->toArray(),
            'min_order' => $model->min_order,
            'base_price' => $model->base_price,
            'per_unit_price' => $model->per_unit_price,
            'process_service_count' => $model->process_service_count,
            'process_good_count' => $model->process_good_count,
            'process_manual_count' => $model->process_manual_count,
            'status' => $model->status,
            'created_at' => $model->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $model->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
