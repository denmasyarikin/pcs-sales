<?php

namespace Denmasyarikin\Sales\Order\Transformers;

use App\Http\Transformers\Pagination;
use Illuminate\Database\Eloquent\Model;

class OrderListTransformer extends Pagination
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
        return (new OrderListDetailTransformer($model))->toArray();
    }
}
