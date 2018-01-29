<?php

namespace Denmasyarikin\Sales\Order\Transformers;

use App\Http\Transformers\Detail;
use Modules\Chanel\Transformers\ChanelDetailTransformer;
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
            'chanel' => (new ChanelDetailTransformer($model->customer->chanel, ['id', 'name', 'type']))->toArray(),
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
