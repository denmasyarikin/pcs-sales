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
            'email' => 'sri@gmail.com'
        ]);

        Customer::create([
            'name' => 'Divisi Produksi',
            'type' => 'internal',
            'address' => 'Karawang',
            'telephone' => '02982902982',
        ]);

        Customer::create([
            'name' => 'Septian Dwi Cahya',
            'address' => 'Karawang',
            'type' => 'agent',
            'email' => 'agent@mail.co',
            'telephone' => '029202202'
        ]);

        Customer::create([
            'name' => 'Flipbox',
            'type' => 'company',
            'address' => 'Karawang',
            'email' => 'agent@mail.co',
            'telephone' => '029202202'
        ]);
    }
}
