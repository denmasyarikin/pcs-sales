<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Product extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('sales_products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 50);
            $table->text('description')->nullable()->default(null);
            $table->integer('unit_id')->unsigned();
            $table->integer('product_group_id')->unsigned()->nullable()->default(null);
            $table->float('min_order');
            $table->float('order_multiples');
            $table->boolean('customizable')->default(true);
            $table->bigInteger('base_price')->default(0);
            $table->bigInteger('per_unit_price')->default(0);
            $table->enum('status', ['draft', 'active', 'inactive'])->default('draft');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('unit_id')->references('id')->on('core_units');
            $table->foreign('product_group_id')->references('id')->on('sales_product_groups');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('sales_products');
    }
}
