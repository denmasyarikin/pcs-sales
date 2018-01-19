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
        $this->call(Product\database\seeds\ProductGroupsTable::class);
        $this->call(Product\database\seeds\ProductsTable::class);
        $this->call(Product\database\seeds\ProductMediasTable::class);
        $this->call(Order\database\seeds\OrdersTable::class);
        $this->call(Payment\database\seeds\PaymentsTable::class);
    }
}
