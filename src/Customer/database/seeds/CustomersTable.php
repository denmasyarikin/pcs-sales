<?php

namespace Denmasyarikin\Sales\Customer\database\seeds;

use Illuminate\Database\Seeder;
use Denmasyarikin\Sales\Customer\Customer;

class CustomersTable extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        Customer::create([
            'name' => 'Sri Haryati',
            'address' => 'Karawang',
        ]);

        Customer::create([
            'name' => 'Septian Dwi Cahya',
            'address' => 'Karawang',
        ]);

        Customer::create([
            'name' => 'Flipbox',
            'type' => 'company',
            'address' => 'Karawang',
        ]);
    }
}
