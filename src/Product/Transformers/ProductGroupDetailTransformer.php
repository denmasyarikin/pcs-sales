<?php

namespace Denmasyarikin\Sales\Product\Transformers;

use App\Http\Transformers\Detail;
use Illuminate\Database\Eloquent\Model;
use Modules\Unit\Transformers\UnitDetailTransformer;

class ProductGroupDetailTransformer extends Detail
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
            'status' => $model->status,
            'created_at' => $model->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $model->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
