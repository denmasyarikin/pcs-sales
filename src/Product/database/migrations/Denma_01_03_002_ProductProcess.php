<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ProductProcess extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('sales_product_processes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id')->unsigned();
            $table->integer('parent_id')->unsigned()->nullable()->default(null);
            $table->enum('type', ['good', 'service', 'manual']);
            $table->enum('type_as', ['good', 'service']);
            $table->integer('reference_id')->nullable()->default(null);
            $table->string('name', 50);
            $table->string('specific', 50)->nullable()->default(null);
            $table->integer('quantity');
            $table->float('base_price')->nullable()->default(null);
            $table->boolean('required')->default(true);
            $table->boolean('static_price')->default(true);
            $table->float('static_to_order_count')->nullable()->default(null);
            $table->integer('unit_id')->unsigned();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('parent_id')->references('id')->on('sales_product_processes');
            $table->foreign('unit_id')->references('id')->on('core_units');
            $table->foreign('product_id')->references('id')->on('sales_products');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('sales_product_processes');
    }
}
