<?php

namespace Denmasyarikin\Sales\Customer\Transformers;

use App\Http\Transformers\Pagination;
use Illuminate\Database\Eloquent\Model;

class CustomerListTransformer extends Pagination
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
        return (new CustomerDetailTransformer($model))->toArray();
    }
}
