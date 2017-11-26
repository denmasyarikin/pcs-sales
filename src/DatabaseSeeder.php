<?php

namespace Denmasyarikin\Sales;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $this->call(Customer\database\seeds\CustomersTable::class);
        $this->call(Bank\database\seeds\BanksTable::class);
    }
}
