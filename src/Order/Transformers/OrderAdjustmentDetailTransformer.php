<?php

namespace Denmasyarikin\Sales\Order\Transformers;

use App\Http\Transformers\Detail;
use Illuminate\Database\Eloquent\Model;

class OrderAdjustmentDetailTransformer extends Detail
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
            'type' => $model->type,
            'priority' => $model->priority,
            'adjustment_origin' => $model->adjustment_origin,
            'adjustment_type' => $model->adjustment_value === null ? 'fixed' : 'percentage',
            'adjustment_value' => $model->adjustment_value,
            'adjustment_total' => $model->adjustment_total,
            'total' => $model->total,
            'created_at' => $model->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $model->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
