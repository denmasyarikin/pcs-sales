<?php

namespace Denmasyarikin\Sales\Customer;

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
            'enabled_direct_email' => true,
            'enabled_direct_call' => true,
            'enabled_direct_whatsapp' => true
        ];
    }
}
