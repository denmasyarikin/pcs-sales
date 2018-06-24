<?php

namespace Denmasyarikin\Sales\Payment\Transformers;

use App\Http\Transformers\Collection;
use Illuminate\Database\Eloquent\Model;

class PaymentListCollectionTransformer extends Collection
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
        return (new PaymentDetailTransformer($model))->toArray();
    }
}
