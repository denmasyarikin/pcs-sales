<?php

namespace Denmasyarikin\Sales\_Tax;

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
            'tax_rate' => 10,
        ];
    }
}
