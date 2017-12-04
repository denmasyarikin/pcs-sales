<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class OrderItem extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('sales_order_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_id')->unsigned();
            $table->enum('type', ['product', 'good', 'service', 'manual']);
            $table->enum('type_as', ['product', 'good', 'service']);
            $table->integer('reference_id')->nullable()->default(null);
            $table->string('name', 50);
            $table->string('specific', 50)->nullable()->default(null);
            $table->text('description')->nullable()->default(null);
            $table->integer('quantity');
            $table->float('unit_price');
            $table->float('unit_total');
            $table->float('adjustment_total')->default(0);
            $table->float('total');
            $table->integer('unit_id')->unsigned();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('order_id')->references('id')->on('sales_orders');
            $table->foreign('unit_id')->references('id')->on('core_units');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('sales_order_items');
    }
}
