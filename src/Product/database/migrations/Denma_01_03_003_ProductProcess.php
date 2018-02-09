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
            $table->float('quantity');
            $table->float('unit_price');
            $table->float('unit_total');
            $table->integer('unit_id')->unsigned();
            $table->boolean('required')->default(true);
            $table->enum('price_type', ['static', 'dynamic'])->default('static');
            $table->float('price_increase_multiples')->nullable()->default(null);
            $table->float('price_increase_percentage')->nullable()->default(null);
            $table->boolean('insheet_required')->default(false);
            $table->enum('insheet_type', ['static', 'dynamic', 'percentage'])->nullable()->default(null);
            $table->float('insheet_value')->nullable()->default(null);
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
