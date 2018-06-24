<?php

namespace Denmasyarikin\Sales\Order\Transformers;

use App\Http\Transformers\Collection;
use Illuminate\Database\Eloquent\Model;

class OrderItemListTransformer extends Collection
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
        return (new OrderItemDetailTransformer($model))->toArray();
    }
}
