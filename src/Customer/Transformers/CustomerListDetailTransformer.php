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
        $chanel = $model->chanel;

        return [
            'id' => $model->id,
            'code' => $model->code,
            'chanel_id' => $model->chanel->id,
            'chanel' => [
                'id' => $chanel->id,
                'name' => $chanel->name,
                'type' => $chanel->type,
            ],
            'name' => $model->name,
            'address' => $model->address,
            'email' => $model->email,
            'telephone' => $model->telephone,
            'contact_person' => $model->contact_person,
            'due_date_type' => $model->due_date_type,
            'due_date_day_count' => $model->due_date_day_count,
            'updated_at' => $model->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
