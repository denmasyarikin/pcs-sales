<?php

namespace Denmasyarikin\Sales\Product\Transformers;

use App\Http\Transformers\Detail;
use Illuminate\Database\Eloquent\Model;
use Modules\Unit\Transformers\UnitDetailTransformer;

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
            'image' => $model->image,
            'unit' => (new UnitDetailTransformer($model->unit, ['id', 'name', 'specific']))->toArray(),
            'customizable' => (bool) $model->customizable,
            'min_order' => $model->min_order,
            'base_price' => $model->base_price,
            'per_unit_price' => $model->per_unit_price,
            'processes_count' => [
                'total' => $model->process_count,
                'service' => $model->process_service_count,
                'good' => $model->process_good_count,
                'manual' => $model->process_manual_count,
            ],
            'processes' => (new ProductProcessListTransformer($model->processes))->toArray(),
            'medias' => (new ProductMediaListTransformer($model->medias))->toArray(),
            'group_ids' => $model->groups->pluck('id'),
            'status' => $model->status,
            'created_at' => $model->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $model->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
