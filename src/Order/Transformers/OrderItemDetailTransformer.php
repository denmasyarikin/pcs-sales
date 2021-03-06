<?php

namespace Denmasyarikin\Sales\Order\Transformers;

use App\Http\Transformers\Detail;
use Illuminate\Database\Eloquent\Model;
use Denmasyarikin\Sales\Order\OrderItem;
use Modules\Unit\Transformers\UnitListDetailTransformer;

class OrderItemDetailTransformer extends Detail
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
            'name' => $model->name,
            'specific' => $model->specific,
            'reference_id' => $model->reference_id,
            'reference_type' => $model->reference_type,
            'reference_configurations' => $model->reference_configurations,
            'formatted' => $model->name.($model->specific ? " ({$model->specific})" : ''),
            'quantity' => $model->quantity,
            'unit_price' => $model->unit_price,
            'unit_total' => $model->unit_total,
            'adjustment_total' => $model->adjustment_total,
            'adjustments' => (new OrderAdjustmentListTransformer($model->getAdjustments()))->toArray(),
            'markup' => (int) $model->markup,
            'markup_rule' => $model->markup_rule,
            'discount' => (int) $model->discount,
            'discount_rule' => $model->discount_rule,
            'voucher' => $model->voucher,
            'total' => $model->total,
            'unit_id' => $model->unit_id,
            'unit' => (new UnitListDetailTransformer($model->unit))->toArray(),
            'note' => $model->note,
            'created_at' => $model->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $model->updated_at->format('Y-m-d H:i:s'),
        ];
    }

    /**
     * get product process.
     *
     * @param Model $model
     *
     * @return array
     */
    protected function getProductProcess(Model $model)
    {
        $items = OrderItem::where('order_id', $model->order_id)
                            ->where('type', 'product')
                            ->where('type_as', '<>', 'product')
                            ->where('reference_id', $model->id)
                            ->get();

        return (new OrderItemListTransformer($items))->toArray();
    }
}
