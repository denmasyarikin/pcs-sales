<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class OrderHistory extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('sales_order_histories', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_id')->unsigned();
            $table->string('label', 50);
            $table->text('description')->nullable()->default(null);
            $table->string('actor_as', 50);
            $table->string('actor_name', 50);
            $table->enum('type', ['order', 'process', 'payment'])->default('order');
            $table->timestamps();

            $table->foreign('order_id')->references('id')->on('sales_orders');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('sales_order_histories');
    }
}
