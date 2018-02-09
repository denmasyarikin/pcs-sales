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
            'id' => (int) $model->id,
            'type' => $model->type,
            'type_as' => $model->type_as,
            'parent_id' =>$model->parent_id,
            'reference_id' => $model->reference_id,
            'name' => $model->name,
            'specific' => $model->specific,
            'formatted' => $model->name.($model->specific ? " ({$model->specific})" : ''),
            'quantity' => (float) $model->quantity,
            'unit_price' => (float) $model->unit_price,
            'unit_total' => (float) $model->unit_total,
            'unit' => (new UnitListDetailTransformer($model->unit))->toArray(),
            'required' => (bool) $model->required,
            'price_type' => (string) $model->price_type,
            'price_increase_multiples' => (float) $model->price_increase_multiples,
            'price_increase_percentage' => (float) $model->price_increase_percentage,
            'insheet_required' => (bool) $model->insheet_required,
            'insheet_type' => (string) $model->insheet_type,
            'insheet_value' => (float) $model->insheet_value,
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
