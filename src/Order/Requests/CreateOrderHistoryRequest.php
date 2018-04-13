<?php

namespace Denmasyarikin\Sales\Order\Requests;

class CreateOrderHistoryRequest extends DetailOrderRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'type' => 'required|in:order,process,payment,delivery',
            'label' => 'required',
        ];
    }
}
