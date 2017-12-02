<?php

namespace Denmasyarikin\Sales\Product\Transformers;

use App\Http\Transformers\Collection;
use Illuminate\Database\Eloquent\Model;

class ProductMediaListTransformer extends Collection
{
    /**
     * get data.
     *
     * @return array
     */
    protected function getData(Model $model)
    {
        return (new ProductMediaDetailTransformer($model))->toArray();
    }
}
