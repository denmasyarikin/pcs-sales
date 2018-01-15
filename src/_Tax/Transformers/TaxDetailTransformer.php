<?php

namespace Denmasyarikin\Sales\_Tax\Transformers;

use App\Http\Transformers\Detail;
use Illuminate\Database\Eloquent\Model;
use Denmasyarikin\Sales\Order\Transformers\OrderListDetailTransformer;

class TaxDetailTransformer extends Detail
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
            'order' => (new OrderListDetailTransformer($model->order))->toArray(),
            'transaction_total' => $model->adjustment_origin,
            'ppn' => $model->adjustment_value,
            'ppn_total' => $model->adjustment_total,
            'total' => $model->total,
            'created_at' => $model->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $model->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
