<?php

namespace Denmasyarikin\Sales\Order\Requests;

class CreateOrderAttachmentRequest extends DetailOrderRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'type' => 'required|in:po,sample',
            'file' => 'required',
            'description' => 'required'
        ];
    }
}
