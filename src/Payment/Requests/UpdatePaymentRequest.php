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
            'cash_total' => 'required_if:payment_method,cash|numeric',
            'cash_back' => 'required_if:payment_method,cash|numeric',
            'bank_id' => 'required_if:payment_method,transfer|exists:sales_banks,id',
            'payment_slip' => 'required_if:payment_method,transfer',
            'pay' => 'required|numeric'
        ];
    }
}
