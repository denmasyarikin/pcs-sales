<?php

namespace Denmasyarikin\Sales\Bank\Transformers;

use App\Http\Transformers\Collection;
use Illuminate\Database\Eloquent\Model;

class BankListTransformer extends Collection
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
        return (new BankDetailTransformer($model))->toArray();
    }
}
