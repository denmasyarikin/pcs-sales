<?php

namespace Denmasyarikin\Sales\Order\database\seeds;

use Illuminate\Database\Seeder;
use Denmasyarikin\Sales\Order\Order;

class OrdersTable extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $order = Order::create([
            'item_count' => 1,
            'items_total' => 54000,
            'total' => 54000,
            'paid' => 0,
            'remaining' => 54000,
            'is_paid' => false,
            'note' => 'Cepet cepet ya...',
            'cs_user_id' => 1,
            'cs_name' => 'Deden Maulana',
            'due_date' => date('Y-m-d H:i:s'),
        ]);

        $order->customer()->create([
            'customer_id' => 1,
            'type' => 'public',
            'name' => 'Sri Haryati',
            'address' => 'Karawang',
            'telephone' => '0897887869',
        ]);

        $order->items()->create([
            'type' => 'product',
            'type_as' => 'product',
            'name' => 'Kartu Nama',
            'quantity' => 1,
            'unit_price' => 54000,
            'unit_total' => 54000,
            'total' => 54000,
            'unit_id' => 1,
        ]);

        $order->items()->create([
            'type' => 'product',
            'type_as' => 'service',
            'reference_id' => 1,
            'name' => 'Setting',
            'specific' => 'Kartu Nama',
            'quantity' => 1,
            'unit_price' => 30000,
            'unit_total' => 30000,
            'total' => 30000,
            'unit_id' => 10,
        ]);

        $order->items()->create([
            'type' => 'product',
            'type_as' => 'service',
            'reference_id' => 2,
            'name' => 'Print Digital',
            'specific' => 'Art Carton',
            'quantity' => 4,
            'unit_price' => 4000,
            'unit_total' => 16000,
            'total' => 16000,
            'unit_id' => 10,
        ]);

        $order->items()->create([
            'type' => 'product',
            'type_as' => 'service',
            'reference_id' => 3,
            'name' => 'Finishing Digital',
            'specific' => 'Laminating Gloys',
            'quantity' => 4,
            'unit_price' => 2000,
            'unit_total' => 8000,
            'total' => 8000,
            'unit_id' => 9,
        ]);
    }
}
