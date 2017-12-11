<?php

namespace Denmasyarikin\Sales\Payment\Transformers;

use App\Http\Transformers\Pagination;
use Illuminate\Database\Eloquent\Model;

class PaymentListTransformer extends Pagination
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
