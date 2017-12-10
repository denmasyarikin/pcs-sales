<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class OrderCustomer extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('sales_order_customers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_id')->unsigned();
            $table->integer('customer_id')->unsigned();
            $table->enum('type', ['public', 'agent', 'company'])->default('public');
            $table->string('name', 50);
            $table->text('address')->nullable()->default(null);
            $table->string('telephone', 20)->nullable()->default(null);
            $table->string('email', 50)->nullable()->default(null);
            $table->string('contact_person', 50)->nullable()->default(null);
            $table->timestamps();

            $table->foreign('order_id')->references('id')->on('sales_orders');
            $table->foreign('customer_id')->references('id')->on('sales_customers');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('sales_order_customers');
    }
}
