<?php

namespace Denmasyarikin\Sales\Payment;

use App\Manager\Contracts\Setting as SettingContract;

class Setting implements SettingContract
{
    /**
     * get setting.
     *
     * @return array
     */
    public function getSetting()
    {
        return [
            'required_payment_ship' => true
        ];
    }
}
