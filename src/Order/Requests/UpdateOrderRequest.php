<?php

namespace Denmasyarikin\Sales\Order\Requests;

class UpdateOrderRequest extends DetailOrderRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'note' => '',
            'due_date' => 'date_format:Y-m-d H:i:s',
            'start_process_date' => 'date_format:Y-m-d H:i:s',
            'end_process_date' => 'date_format:Y-m-d H:i:s',
            'close_date' => 'date_format:Y-m-d H:i:s',
            'status' => 'in:created,processing,finished,archived'
        ];
    }
}
