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
            'type' => $model->type,
            'name' => $model->name,
            'address' => $model->address,
            'email' => $model->email,
            'telephone' => $model->telephone,
            'contact_person' => $model->contact_person
        ];
    }
}
