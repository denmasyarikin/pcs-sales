<?php

namespace Denmasyarikin\Sales\Customer\Transformers;

use App\Http\Transformers\Detail;
use Illuminate\Database\Eloquent\Model;
use Modules\Chanel\Transformers\ChanelDetailTransformer;

class CustomerDetailTransformer extends Detail
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
            'chanel' => (new ChanelDetailTransformer($model->chanel))->toArray(),
            'name' => $model->name,
            'address' => $model->address,
            'telephone' => $model->telephone,
            'email' => $model->email,
            'contact_person' => $model->contact_person,
            'last_order' => $model->last_order,
            'created_at' => $model->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $model->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
