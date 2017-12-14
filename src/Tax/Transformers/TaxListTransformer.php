<?php

namespace Denmasyarikin\Sales\Tax\Transformers;

use App\Http\Transformers\Pagination;
use Illuminate\Database\Eloquent\Model;

class TaxListTransformer extends Pagination
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
        return (new TaxDetailTransformer($model))->toArray();
    }
}
