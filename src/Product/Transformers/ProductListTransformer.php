<?php

namespace Denmasyarikin\Sales\Product\Transformers;

use App\Http\Transformers\Pagination;
use Illuminate\Database\Eloquent\Model;

class ProductListTransformer extends Pagination
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
        return (new ProductListDetailTransformer($model))->toArray();
    }
}
