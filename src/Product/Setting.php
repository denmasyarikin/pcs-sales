<?php

namespace Denmasyarikin\Sales\Product;

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
            'default_image' => '/api/media/image/sales/product/image/default.jpeg',
            'enable_input_manual' => true,
        ];
    }
}
