<?php

namespace Denmasyarikin\Sales\Order\Requests;

class UpdateStatusOrderRequest extends DetailOrderRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'status' => 'required|in:created,processing,finished,archived',
        ];
    }
}
