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
            $table->integer('chanel_id')->unsigned();
            $table->float('item_total')->default(0);
            $table->float('adjustment_total')->default(0);
            $table->float('total')->default(0);
            $table->float('paid_off')->default(0);
            $table->float('remaining')->default(0);
            $table->boolean('paid')->default(false);
            $table->text('note')->nullable()->default(null);
            $table->integer('cs_user_id')->unsigned();
            $table->string('cs_name', 50);
            $table->timestamp('due_date')->nullable()->default(null);
            $table->timestamp('start_process_date')->nullable()->default(null);
            $table->timestamp('end_process_date')->nullable()->default(null);
            $table->timestamp('close_date')->nullable()->default(null);
            $table->enum('status', ['draft', 'created', 'processing', 'finished', 'closed', 'canceled'])->default('draft');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('cs_user_id')->references('id')->on('core_users');
            $table->foreign('chanel_id')->references('id')->on('core_chanels');
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
