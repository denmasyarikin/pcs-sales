<?php

namespace Denmasyarikin\Sales\Product\Transformers;

use App\Http\Transformers\Detail;
use Illuminate\Database\Eloquent\Model;
use Modules\Unit\Transformers\UnitDetailTransformer;

class ProductProcessDetailTransformer extends Detail
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
            'process_type' => $model->process_type,
            'process_type_as' => $model->process_type_as,
            'parent_id' => $model->parent_id,
            'reference_id' => $model->reference_id,
            'name' => $model->name,
            'type' => $model->type,
            'quantity' => $model->quantity,
            'base_price' => $model->base_price,
            'required' => (bool) $model->required,
            'static_price' => (bool) $model->static_price,
            'static_to_order_count' => $model->static_to_order_count,
            'unit' => (new UnitDetailTransformer($model->unit, ['id', 'name', 'specific']))->toArray(),
            'created_at' => $model->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $model->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
