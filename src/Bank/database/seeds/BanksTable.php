<?php

namespace Denmasyarikin\Sales\Bank\database\seeds;

use Illuminate\Database\Seeder;
use Denmasyarikin\Sales\Bank\Bank;

class BanksTable extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        Bank::create([
            'name' => 'Mandiri',
            'account_name' => 'Deden Maulana',
            'account_number' => '0292029202'
        ]);
    }
}
