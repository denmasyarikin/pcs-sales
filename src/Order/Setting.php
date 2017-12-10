<?php

namespace Denmasyarikin\Sales\Order;

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
            'enabled_adjusment' => true,
            'enabled_tax' => true,
            'enabled_voucher' => true,
            'enabled_discount' => true,
            'cancelation_reasons' => [
                'replaced',
                'customer_request',
                'prdouction_error',
                'rejected',
                'unknown',
                'other',
            ],
        ];
    }
}
