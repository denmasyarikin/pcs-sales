<?php

namespace Denmasyarikin\Sales\Payment\Requests;

use App\Http\Requests\FormRequest;
use Denmasyarikin\Sales\Payment\Payment;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DetailPaymentRequest extends FormRequest
{
    /**
     * payment.
     *
     * @var Payment
     */
    public $payment;

    /**
     * get payment.
     *
     * @param bool $refresh
     *
     * @return Payment
     */
    public function getPayment($refresh = true): ?Payment
    {
        if ($this->payment and !$refresh) {
            return $this->payment;
        }

        $id = (int) $this->route('id');

        if ($this->payment = Payment::find($id)) {
            return $this->payment;
        }

        throw new NotFoundHttpException('Payment Not Found');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [];
    }
}
