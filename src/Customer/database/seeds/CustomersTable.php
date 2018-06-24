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
            'chanel_id' => 1,
            'name' => 'Sri Haryati',
            'address' => 'Karawang',
            'email' => 'sri@gmail.com',
        ]);

        Customer::create([
            'chanel_id' => 4,
            'name' => 'Divisi Produksi',
            'address' => 'Karawang',
            'telephone' => '02982902982',
        ]);

        Customer::create([
            'chanel_id' => 3,
            'name' => 'Septian Dwi Cahya',
            'address' => 'Karawang',
            'email' => 'agent@mail.co',
            'telephone' => '029202202',
        ]);

        Customer::create([
            'chanel_id' => 2,
            'name' => 'Flipbox',
            'address' => 'Karawang',
            'email' => 'agent@mail.co',
            'telephone' => '029202202',
        ]);
    }
}
