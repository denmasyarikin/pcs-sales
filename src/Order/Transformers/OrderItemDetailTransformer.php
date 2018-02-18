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
            'type_as' => $model->type_as,
            'reference_id' => $model->reference_id,
            'reference_type' => $model->reference_type,
            'reference_second_id' => $model->reference_second_id,
            'process' => $model->isProduct() ? $this->getProductProcess($model) : [],
            'name' => $model->name,
            'specific' => $model->specific,
            'formatted' => $model->name.($model->specific ? " ({$model->specific})" : ''),
            'note' => $model->note,
            'quantity' => $model->quantity,
            'unit_price' => $model->unit_price,
            'unit_total' => $model->unit_total,
            'depending_to_dimension' => (bool) $model->depending_to_dimension,
            'dimension' => $model->dimension,
            'dimension_unit' => (new UnitListDetailTransformer($model->dimensionUnit))->toArray(),
            'length' => (float) $model->length,
            'width' => (float) $model->width,
            'height' => (float) $model->height,
            'weight' => (float) $model->weight,
            'price_type' => $model->price_type,
            'price_increase_multiples' => $model->price_increase_multiples,
            'price_increase_percentage' => $model->price_increase_percentage,
            'adjustment_total' => $model->adjustment_total,
            'adjustments' => (new OrderAdjustmentListTransformer($model->getAdjustments()))->toArray(),
            'insheet_required' => (bool) $model->insheet_required,
            'insheet_type' => $model->insheet_type,
            'insheet_multiples' => $model->insheet_multiples,
            'insheet_quantity' => $model->insheet_quantity,
            'insheet_added' => $model->insheet_added,
            'markup' => $model->markup,
            'discount' => $model->discount,
            'voucher' => $model->voucher,
            'total' => $model->total,
            'unit' => (new UnitListDetailTransformer($model->unit))->toArray(),
            'created_at' => $model->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $model->updated_at->format('Y-m-d H:i:s'),
        ];
    }
    /**
     * get product process
     *
     * @param Model $model
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
