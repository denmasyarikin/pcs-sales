<?php

namespace Denmasyarikin\Sales\Order\Transformers;

use App\Http\Transformers\Detail;
use Illuminate\Database\Eloquent\Model;

class OrderDetailTransformer extends Detail
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
            'item_count' => $model->item_count,
            'items_total' => $model->items_total,
            'adjustment_total' => $model->adjustment_total,
            'discount' => $model->discount,
            'tax' => $model->tax,
            'tax_in_price' => $model->tax_in_price,
            'total' => $model->total,
            'paid' => $model->paid,
            'remaining' => $model->remaining,
            'is_paid' => (bool) $model->is_paid,
            'note' => $model->note,
            'cs_user_id' => $model->cs_user_id,
            'cs_name' => $model->cs_name,
            'due_date' => $model->due_date,
            'start_process_date' => $model->start_process_date,
            'end_process_date' => $model->end_process_date,
            'close_date' => $model->close_date,
            'status' => $model->status,
            'created_at' => $model->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $model->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
