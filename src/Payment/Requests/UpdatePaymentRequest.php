<?php

namespace Denmasyarikin\Sales\Payment\Requests;

use Denmasyarikin\Sales\Payment\Payment;

class UpdatePaymentRequest extends DetailPaymentRequest
{
    /**
     * get payment.
     *
     * @param bool $refresh
     *
     * @return Payment
     */
    public function getPayment($refresh = true): ?Payment
    {
        $payment = parent::getPayment();

        $this->checkFreshData($payment);

        return $payment;
    }

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
