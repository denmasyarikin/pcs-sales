<?php

namespace Denmasyarikin\Sales\Payment\Requests;

class UpdatePaymentRequest extends DetailPaymentRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'payment_method' => 'required|in:cash,transfer',
            'payment_method' => 'required|in:cash,transfer',
            'payment_slip' => 'nullable|required_if:payment_method,transfer',
            'pay' => 'required|numeric',
            'account_id' => 'nullable|numeric',
        ];
    }
}
