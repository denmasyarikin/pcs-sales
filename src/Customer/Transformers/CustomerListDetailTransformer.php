<?php

namespace Denmasyarikin\Sales\Customer\Transformers;

use App\Http\Transformers\Detail;
use Illuminate\Database\Eloquent\Model;

class CustomerListDetailTransformer extends Detail
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
            'chanel_id' => $model->chanel->id,
            'chanel_type' => $model->chanel->type,
            'name' => $model->name,
            'address' => $model->address,
            'email' => $model->email,
            'telephone' => $model->telephone,
            'contact_person' => $model->contact_person,
            'updated_at' => $model->updated_at->format('Y-m-d H:i:s')
        ];
    }
}
