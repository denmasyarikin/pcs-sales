<?php

namespace Denmasyarikin\Sales\Order\Transformers;

use App\Http\Transformers\Detail;
use Illuminate\Database\Eloquent\Model;
use Modules\Chanel\Transformers\ChanelDetailTransformer;
use Denmasyarikin\Sales\Payment\Transformers\PaymentListCollectionTransformer;

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
            'code' => $model->code,
            'chanel_id' => $model->chanel_id,
            'chanel' => (new ChanelDetailTransformer($model->chanel))->toArray(),
            'customer' => $model->customer ? (new OrderCustomerTransformer($model->customer))->toArray() : null,
            'item_total' => $model->item_total,
            'items' => (new OrderItemListTransformer($model->getPrimaryItems()))->toArray(),
            'adjustment_total' => $model->adjustment_total,
            'total' => $model->total,
            'paid_off' => $model->paid_off,
            'remaining' => $model->remaining,
            'paid' => (bool) $model->paid,
            'payments' => (new PaymentListCollectionTransformer($model->payments))->toArray(),
            'note' => $model->note,
            'cs_user_id' => $model->cs_user_id,
            'cs_name' => $model->cs_name,
            'due_date' => $model->due_date,
            'over_due_date' => (bool) $model->over_due_date,
            'estimated_finish_date' => $model->estimated_finish_date,
            'over_estimate' => (bool) $model->over_estimate,
            'start_process_date' => $model->start_process_date,
            'end_process_date' => $model->end_process_date,
            'taken_date' => $model->taken_date,
            'close_date' => $model->close_date,
            'status' => $model->status,
            'adjustments' => (new OrderAdjustmentListTransformer($model->getAdjustments()))->toArray(),
            'histories' => (new OrderHistoryListTransformer($model->histories->sortByDesc('id')))->toArray(),
            'attachments' => (new OrderAttachmentListTransformer($model->attachments))->toArray(),
            'cancelation' => $model->cancelation ? (new OrderCancelationDetailTransformer($model->cancelation))->toArray() : null,
            'created_at' => $model->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $model->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
