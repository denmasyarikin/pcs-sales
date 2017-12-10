<?php

namespace Denmasyarikin\Sales\Order\Transformers;

use App\Http\Transformers\Detail;
use Illuminate\Database\Eloquent\Model;
use Modules\Unit\Transformers\UnitDetailTransformer;

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
            'type_as' => $model->type_as,
            'reference_id' => $model->reference_id,
            'name' => $model->name,
            'specific' => $model->specific,
            'note' => $model->note,
            'quantity' => $model->quantity,
            'unit_price' => $model->unit_price,
            'unit_total' => $model->unit_total,
            'adjustment_total' => $model->adjustment_total,
            'markup' => $model->markup,
            'discount' => $model->discount,
            'voucher' => $model->voucher,
            'total' => $model->total,
            'unit' => (new UnitDetailTransformer($model->unit, ['id', 'name', 'specific']))->toArray(),
            'created_at' => $model->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $model->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
