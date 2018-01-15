<?php

namespace Denmasyarikin\Sales;

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
            'material_icon' => 'receipt',
            'tagline' => 'Sistem Penjualan',
        ];
    }
}
