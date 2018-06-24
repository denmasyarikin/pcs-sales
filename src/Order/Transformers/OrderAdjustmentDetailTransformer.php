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
            'sequence' => $model->sequence,
            'adjustment_rule' => $model->adjustment_rule,
            'adjustment_value' => $model->adjustment_value,
            'before_adjustment' => $model->before_adjustment,
            'adjustment_total' => $model->adjustment_total,
            'after_adjustment' => $model->after_adjustment,
            'created_at' => $model->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $model->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
