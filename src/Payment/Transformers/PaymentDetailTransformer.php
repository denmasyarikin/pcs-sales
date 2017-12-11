<?php

namespace Denmasyarikin\Sales\Payment\Transformers;

use App\Http\Transformers\Detail;
use Illuminate\Database\Eloquent\Model;
use Denmasyarikin\Sales\Bank\Transformers\BankDetailTransformer;
use Denmasyarikin\Sales\Order\Transformers\OrderCustomerTransformer;

class PaymentDetailTransformer extends Detail
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
            'order_id' => (int) $model->id,
            'customer' => (new OrderCustomerTransformer($model->customer))->toArray(),
            'type' => $model->type,
            'payment_method' => $model->payment_method,
            'cash_total' => (float) $model->cash_total,
            'cash_back' => (float) $model->cash_back,
            'bank' => !is_null($model->bank)
                        ? (new BankDetailTransformer($model->bank))->toArray()
                        : null,
            'payment_slip' => $model->payment_slip,
            'order_total' => (float) $model->order_total,
            'payment_total' => (float) $model->payment_total,
            'pay' => (float) $model->pay,
            'remaining' => (float) $model->remaining,
            'cs_name' => $model->cs_name,
            'created_at' => $model->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $model->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
