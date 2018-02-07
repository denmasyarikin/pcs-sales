<?php

namespace Denmasyarikin\Sales\Payment\Requests;

use App\Http\Requests\FormRequest;

class CreatePaymentRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'order_id' => 'required|exists:sales_orders,id',
            'payment_method' => 'required|in:cash,transfer',
            'cash_total' => 'nullable|required_if:payment_method,cash|numeric',
            'cash_back' => 'nullable|required_if:payment_method,cash|numeric',
            'bank_id' => 'nullable|required_if:payment_method,transfer|exists:sales_banks,id',
            'payment_slip' => 'nullable|required_if:payment_method,transfer',
            'pay' => 'required|numeric',
        ];
    }
}
