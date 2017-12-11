<?php

namespace Denmasyarikin\Sales\Order\Transformers;

use App\Http\Transformers\Detail;
use Illuminate\Database\Eloquent\Model;

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
            'customer' => $model->customer ? (new OrderCustomerTransformer($model->customer))->toArray() : null,
            'item_count' => [
                'total' => $model->item_count,
                'product' => $model->item_product_count,
                'product_process' => $model->item_product_process_count,
                'service' => $model->item_service_count,
                'good' => $model->item_good_count,
                'manual' => $model->item_manual_count,
            ],
            'total' => $model->total,
            'cs_user_id' => $model->cs_user_id,
            'cs_name' => $model->cs_name,
            'due_date' => $model->due_date,
            'status' => $model->status,
            'due_date' => $model->due_date,
            'start_process_date' => $model->start_process_date,
            'end_process_date' => $model->end_process_date,
            'close_date' => $model->close_date,
            'created_at' => $model->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $model->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
