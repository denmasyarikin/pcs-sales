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
            'payment_slip' => 'nullable|required_if:payment_method,transfer',
            'pay' => 'required|numeric',
            'account_id' => 'nullable|numeric',
        ];
    }
}
