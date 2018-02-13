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
            'unit_price' => $model->unit_price,
            'unit_total' => $model->unit_total,
            'unit' => (new UnitListDetailTransformer($model->unit))->toArray(),
            'required' => (bool) $model->required,
            'depending_to_dimension' => (bool) $model->depending_to_dimension,
            'dimension' => $model->dimension,
            'dimension_unit' => (new UnitListDetailTransformer($model->dimensionUnit))->toArray(),
            'length' => (float) $model->length,
            'width' => (float) $model->width,
            'height' => (float) $model->height,
            'weight' => (float) $model->weight,
            'price_type' => (string) $model->price_type,
            'price_increase_multiples' => (float) $model->price_increase_multiples,
            'price_increase_percentage' => (float) $model->price_increase_percentage,
            'insheet_required' => (bool) $model->insheet_required,
            'insheet_type' => (string) $model->insheet_type,
            'insheet_multiples' => (float) $model->insheet_multiples,
            'insheet_quantity' => (float) $model->insheet_quantity,
            'insheet_added' => (float) $model->insheet_added,
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
