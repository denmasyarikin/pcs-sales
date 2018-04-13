<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class OrderItemAdjustment extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('sales_order_item_adjustments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_item_id')->unsigned();
            $table->integer('priority');
            $table->enum('type', ['markup', 'discount', 'voucher']);
            $table->string('adjustment_value')->nullable()->default(null);
            $table->bigInteger('adjustment_origin')->default(0);
            $table->bigInteger('adjustment_total')->default(0);
            $table->bigInteger('total')->default(0);
            $table->timestamps();

            $table->foreign('order_item_id')->references('id')->on('sales_order_items');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('sales_order_item_adjustments');
    }
}
