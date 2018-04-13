<?php

namespace Denmasyarikin\Sales\Order\Transformers;

use App\Http\Transformers\Detail;
use Illuminate\Database\Eloquent\Model;
use Denmasyarikin\Sales\Customer\Transformers\CustomerListDetailTransformer;

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
            'customer_id' => (int) $model->customer_id,
            'customer' => (new CustomerListDetailTransformer($model->customer))->toArray(),
            'name' => $model->name,
            'address' => $model->address,
            'telephone' => $model->telephone,
            'email' => $model->email,
            'contact_person' => $model->contact_person,
            'created_at' => $model->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $model->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
