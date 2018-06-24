<?php

namespace Denmasyarikin\Sales\Order\Transformers;

use App\Http\Transformers\Detail;
use Illuminate\Database\Eloquent\Model;
use Modules\Chanel\Transformers\ChanelDetailTransformer;

class OrderListDetailTransformer extends Detail
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
            'code' => $model->code,
            'chanel' => (new ChanelDetailTransformer($model->chanel, ['id', 'name', 'type']))->toArray(),
            'customer' => $model->customer ? (new OrderCustomerTransformer($model->customer))->toArray() : null,
            'item_count' => $model->item_count,
            'total' => $model->total,
            'paid' => (bool) $model->paid,
            'paid_off' => $model->paid_off,
            'remaining' => $model->remaining,
            'cs_name' => $model->cs_name,
            'status' => $model->status,
            'due_date' => $model->due_date,
            'over_due_date' => (bool) $model->over_due_date,
            'estimated_finish_date' => $model->estimated_finish_date,
            'over_estimate' => (bool) $model->over_estimate,
            'start_process_date' => $model->start_process_date,
            'end_process_date' => $model->end_process_date,
            'taken_date' => $model->taken_date,
            'close_date' => $model->close_date,
            'cancelation' => $model->cancelation ? (new OrderCancelationDetailTransformer($model->cancelation))->toArray() : null,
            'created_at' => $model->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $model->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
