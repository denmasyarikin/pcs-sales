<?php

namespace Denmasyarikin\Sales\Order\Transformers;

use App\Http\Transformers\Detail;
use Illuminate\Database\Eloquent\Model;

class OrderCancelationDetailTransformer extends Detail
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
            'reason' => $model->reason,
            'description' => $model->description,
            'created_at' => $model->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $model->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
