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
            'order_id' => (int) $model->order_id,
            'order_code' => $model->order->code,
            'customer' => (new OrderCustomerTransformer($model->customer))->toArray(),
            'type' => $model->type,
            'payment_method' => $model->payment_method,
            'payment_slip' => $model->payment_slip,
            'order_total' => (float) $model->order_total,
            'payment_total' => (float) $model->payment_total,
            'pay' => (float) $model->pay,
            'remaining' => (float) $model->remaining,
            'cs_name' => $model->cs_name,
            'account_id' => $model->account_id,
            'pay_debt' => $model->updated_at->format('Y-m-d') !== $model->order->created_at->format('Y-m-d'),
            'created_at' => $model->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $model->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
