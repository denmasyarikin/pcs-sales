<?php

namespace Denmasyarikin\Sales\Product\Transformers;

use App\Http\Transformers\Detail;
use Illuminate\Database\Eloquent\Model;
use Modules\Unit\Transformers\UnitListDetailTransformer;

class ProductDetailTransformer extends Detail
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
            'name' => $model->name,
            'description' => $model->description,
            'image' => $model->image,
            'unit' => (new UnitListDetailTransformer($model->unit]))->toArray(),
            'customizable' => (bool) $model->customizable,
            'min_order' => $model->min_order,
            'base_price' => $model->base_price,
            'per_unit_price' => $model->per_unit_price,
            'process_count' => $model->process_count,
            'product_group' => (new ProductGroupDetailTransformer($model->group))->toArray(),
            'status' => $model->status,
            'created_at' => $model->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $model->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
