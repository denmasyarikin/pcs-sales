<?php

namespace Denmasyarikin\Sales\Order\Requests;

use App\Manager\Facades\Setting;

class CancelOrderRequest extends DetailOrderRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $reasons = Setting::get('system.sales.order.cancelation_reasons', []);

        return [
            'reason' => 'required|in:'.implode(',', $reasons),
            'descirption' => 'min:3',
        ];
    }
}
