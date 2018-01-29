<?php

namespace Denmasyarikin\Sales\Order\Requests;

use App\Http\Requests\FormRequest;

class CreateOrderRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
        	'chanel_id' => 'required|exists:core_chanels,id'
        ];
    }
}
