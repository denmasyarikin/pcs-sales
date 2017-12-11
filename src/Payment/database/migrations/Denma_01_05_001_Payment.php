<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Payment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_payments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_id')->unsigned();
            $table->integer('order_customer_id')->unsigned();
            $table->enum('type', ['down_payment', 'rest_payment', 'settlement'])->default('down_payment');
            $table->enum('payment_method', ['cash', 'transfer']);
            $table->float('cash_total')->nullable()->default(null);
            $table->float('cash_back')->nullable()->default(null);
            $table->integer('bank_id')->unsigned()->nullable()->default(null);
            $table->text('payment_slip')->nullable()->default(null);
            $table->float('order_total');
            $table->float('payment_total');
            $table->float('pay');
            $table->float('remaining');
            $table->integer('cs_user_id')->unsigned();
            $table->string('cs_name', 50);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('cs_user_id')->references('id')->on('core_users');
            $table->foreign('order_id')->references('id')->on('sales_orders');
            $table->foreign('order_customer_id')->references('id')->on('sales_order_customers');
            $table->foreign('bank_id')->references('id')->on('sales_banks');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sales_payments');
    }
}
