<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Order extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('item_count');
            $table->float('items_total');
            $table->float('adjustment_total');
            $table->float('discount');
            $table->float('total');
            $table->flaot('paid')->default(false);
            $table->flaot('remaining');
            $table->boolean('is_paid')->default(false);
            $table->flaot('tax');
            $table->boolean('tax_in_price')->default(false);
            $table->enum('status', ['created', 'process', 'finish', 'archived'])->default('created');
            $table->text('note')->nullable()->default(null);
            $table->integer('cs_user_id')->unsigned();
            $table->string('cs_name', 50);
            $table->timestamp('due_date');
            $table->timestamp('start_process_date');
            $table->timestamp('end_process_date');
            $table->timestamp('close_date');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sales_orders');
    }
}
