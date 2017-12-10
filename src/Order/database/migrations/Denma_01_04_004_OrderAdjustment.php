<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class OrderAdjustment extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('sales_order_adjustments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_id')->unsigned();
            $table->integer('priority');
            $table->enum('type', ['discount', 'voucher', 'tax']);
            $table->float('adjustment_origin');
            $table->string('adjustment_value');
            $table->float('adjustment_total');
            $table->float('total');
            $table->timestamps();

            $table->foreign('order_id')->references('id')->on('sales_orders');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('sales_order_adjustments');
    }
}
