<?php

namespace Denmasyarikin\Sales\Order\Requests;

class ChangeEstimatedFinishDateRequest extends DetailOrderRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'to' => 'required|date-format:Y-m-d H:i:s',
        ];
    }
}
