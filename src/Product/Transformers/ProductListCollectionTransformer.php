<?php

namespace Denmasyarikin\Sales\Product\Transformers;

use App\Http\Transformers\Collection;
use Illuminate\Database\Eloquent\Model;

class ProductListCollectionTransformer extends Collection
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
