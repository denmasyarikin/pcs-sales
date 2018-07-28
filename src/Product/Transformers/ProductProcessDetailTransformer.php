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
            'reference_id' => $model->reference_id,
            'reference_type' => $model->reference_type,
            'reference_default_id' => $model->reference_default_id,
            'reference_configurations' => $model->reference_configurations,
            'name' => $model->name,
            'specific' => $model->specific,
            'formatted' => $model->name.($model->specific ? " ({$model->specific})" : ''),
            'quantity' => (float) $model->quantity,
            'unit_price' => $model->unit_price,
            'unit_total' => $model->unit_total,
            'unit_id' => $model->unit_id,
            'unit' => (new UnitListDetailTransformer($model->unit))->toArray(),
            'required' => (bool) $model->required,
            'ratio_order_quantity' => $model->ratio_order_quantity,
            'ratio_process_quantity' => $model->ratio_process_quantity,
            'service_configurable' => (bool) $model->configurable,
            'good_insheet' => (bool) $model->good_insheet,
            'good_insheet_multiples' => (float) $model->good_insheet_multiples,
            'good_insheet_quantity' => (float) $model->good_insheet_quantity,
            'good_insheet_default' => (float) $model->good_insheet_default,
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
