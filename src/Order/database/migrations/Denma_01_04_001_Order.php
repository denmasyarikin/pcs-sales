<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Order extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('sales_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('item_count');
            $table->float('items_total');
            $table->float('adjustment_total')->default(0);
            $table->float('discount')->default(0);
            $table->float('tax')->default(0);
            $table->boolean('tax_in_price')->default(false);
            $table->float('total');
            $table->float('paid')->default(false);
            $table->float('remaining');
            $table->boolean('is_paid')->default(false);
            $table->text('note')->nullable()->default(null);
            $table->integer('cs_user_id')->unsigned();
            $table->string('cs_name', 50);
            $table->timestamp('due_date');
            $table->timestamp('start_process_date')->nullable()->default(null);
            $table->timestamp('end_process_date')->nullable()->default(null);
            $table->timestamp('close_date')->nullable()->default(null);
            $table->enum('status', ['created', 'processing', 'finished', 'archived', 'canceled'])->default('created');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('cs_user_id')->references('id')->on('core_users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('sales_orders');
    }
}
