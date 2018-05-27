<?php

namespace Denmasyarikin\Sales\Order\Transformers;

use App\Http\Transformers\Detail;
use Illuminate\Database\Eloquent\Model;

class OrderCustomerTransformer extends Detail
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
            'customer_id' => $model->customer_id,
            'name' => $model->name,
            'address' => $model->address,
            'telephone' => $model->telephone,
            'email' => $model->email,
            'contact_person' => $model->contact_person,
            'due_date_type' => $model->due_date_type,
            'due_date_day_count' => $model->due_date_day_count,
            'created_at' => $model->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $model->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
