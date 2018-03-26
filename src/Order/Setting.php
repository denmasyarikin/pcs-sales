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
            'enabled_adjustment' => true,
            'enabled_tax' => true,
            'enabled_voucher' => true,
            'enabled_discount' => true,
            'cancelation_reasons' => [
                'Salah input',
                'Permintaan Konsumen',
                'Error produksi',
                'Ditolak',
                'Tidak diketahui',
                'Lainnya',
            ],
            'enable_input_manual' => true
        ];
    }
}
