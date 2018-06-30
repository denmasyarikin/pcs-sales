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
            $table->integer('product_category_id')->unsigned()->nullable()->default(null);
            $table->float('min_order')->default(1);
            $table->float('order_multiples')->default(1);
            $table->enum('status', ['draft', 'active', 'inactive'])->default('draft');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('unit_id')->references('id')->on('core_units');
            $table->foreign('product_category_id')->references('id')->on('sales_product_categories');
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
