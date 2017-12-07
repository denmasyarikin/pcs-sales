<?php

namespace Denmasyarikin\Sales\Tax;

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
            'tax_rate' => 10
        ];
    }
}
