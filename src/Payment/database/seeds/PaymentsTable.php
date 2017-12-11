<?php

namespace Denmasyarikin\Sales\Payment\database\seeds;

use Illuminate\Database\Seeder;
use Denmasyarikin\Sales\Payment\Payment;

class PaymentsTable extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        Payment::create([
            'order_id' => 1,
            'order_customer_id' => 1,
            'type' => 'down_payment',
            'payment_method' => 'cash',
            'cash_total' => 30000,
            'cash_back' => 0,
            'payment_total' => 70000,
            'pay' => 30000,
            'remaining' => 40000,
            'cs_user_id' => 1,
            'cs_name' => 'Denma Syarikin'
        ]);

        Payment::create([
            'order_id' => 1,
            'order_customer_id' => 1,
            'type' => 'rest_payment',
            'payment_method' => 'cash',
            'cash_total' => 50000,
            'cash_back' => 30000,
            'payment_total' => 70000,
            'pay' => 20000,
            'remaining' => 20000,
            'cs_user_id' => 1,
            'cs_name' => 'Denma Syarikin'
        ]);

        Payment::create([
            'order_id' => 1,
            'order_customer_id' => 1,
            'type' => 'settlement',
            'payment_method' => 'transfer',
            'payment_total' => 70000,
            'pay' => 20000,
            'remaining' => 0,
            'bank_id' => 1,
            'payment_slip' => 'api/media/image/sales/payment/payment_slip/bukti.jpeg',
            'cs_user_id' => 1,
            'cs_name' => 'Denma Syarikin'
        ]);
    }
}
