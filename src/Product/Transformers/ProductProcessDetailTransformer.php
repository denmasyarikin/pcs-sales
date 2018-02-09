<?php

namespace Denmasyarikin\Sales\Product\Transformers;

use App\Http\Transformers\Detail;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Modules\Unit\Transformers\UnitListDetailTransformer;

class ProductProcessDetailTransformer extends Detail
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
            'parent_id' => $model->parent_id,
            'reference_id' => $model->reference_id,
            'name' => $model->name,
            'specific' => $model->specific,
            'formatted' => $model->name.($model->specific ? " ({$model->specific})" : ''),
            'quantity' => $model->quantity,
            'base_price' => $model->base_price,
            'unit_price' => $model->base_price * $model->quantity,
            'required' => (bool) $model->required,
            'static_price' => (bool) $model->static_price,
            'static_to_order_count' => $model->static_to_order_count,
            'unit' => (new UnitListDetailTransformer($model->unit))->toArray(),
            'options' => $this->getOptions($model->children),
            'created_at' => $model->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $model->updated_at->format('Y-m-d H:i:s'),
        ];
    }

    /**
     * get options.
     *
     * @param Collection $options
     *
     * @return array
     */
    protected function getOptions(Collection $options)
    {
        $data = [];

        foreach ($options as $child) {
            $data[] = (new self($child))->toArray();
        }

        return $data;
    }
}
