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
            $table->integer('min_order');
            $table->boolean('customizable')->default(true);
            $table->float('base_price')->default(0);
            $table->float('per_unit_price')->default(0);
            $table->integer('process_service_count')->default(0);
            $table->integer('process_good_count')->default(0);
            $table->integer('process_manual_count')->default(0);
            $table->enum('status', ['draft', 'active', 'inactive'])->default('draft');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('unit_id')->references('id')->on('core_units');
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
