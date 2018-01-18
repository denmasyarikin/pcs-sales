<?php

namespace Denmasyarikin\Sales\Bank\Transformers;

use App\Http\Transformers\Detail;
use Illuminate\Database\Eloquent\Model;

class BankDetailTransformer extends Detail
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
        return [
            'id' => $model->id,
            'name' => $model->name,
            'logo' => $model->logo,
            'account_name' => $model->account_name,
            'account_number' => $model->account_number,
            'created_at' => $model->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $model->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
