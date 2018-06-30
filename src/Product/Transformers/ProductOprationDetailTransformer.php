<?php

namespace Denmasyarikin\Sales\Product\Transformers;

use App\Http\Transformers\Detail;
use Illuminate\Database\Eloquent\Model;

class ProductOprationDetailTransformer extends Detail
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
            'product_configuration_id' => $model->product_configuration_id,
            'product_configuration' => (new ProductConfigurationDetailTransformer($model->productConfiguration))->toArray(),
            'condition' => $model->condition,
            'condition_value' => $model->condition_value,
            'opration' => $model->opration,
            'opration_value' => $model->opration_value,
            'created_at' => $model->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $model->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
