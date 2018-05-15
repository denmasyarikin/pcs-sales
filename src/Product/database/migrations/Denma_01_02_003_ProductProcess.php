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
            $table->string('reference_type')->nullable()->default(null);
            $table->longText('reference_configuration')->nullable()->default(null);
            $table->string('name', 50);
            $table->string('specific', 50)->nullable()->default(null);
            $table->float('quantity');
            $table->bigInteger('unit_price');
            $table->bigInteger('unit_total');
            $table->integer('unit_id')->unsigned();
            $table->boolean('required')->default(true);
            // dimension
            $table->boolean('depending_to_dimension')->default(false);
            $table->enum('dimension', ['length', 'area', 'volume', 'weight'])->nullable()->default(null);
            $table->integer('dimension_unit_id')->unsigned()->nullable()->default(null);
            $table->float('length')->nullable()->default(null);
            $table->float('width')->nullable()->default(null);
            $table->float('height')->nullable()->default(null);
            $table->float('weight')->nullable()->default(null);
            // increasment
            $table->boolean('increasement')->default(false);
            $table->float('increasement_multiples')->nullable()->default(null);
            $table->enum('increasement_rule', ['fixed', 'percentage'])->nullable()->default(null);
            $table->float('increasement_value')->nullable()->default(null);
            // insheet
            $table->boolean('insheet_required')->default(false);
            $table->enum('insheet_type', ['static', 'dynamic'])->nullable()->default(null);
            $table->float('insheet_multiples')->nullable()->default(null);
            $table->float('insheet_quantity')->nullable()->default(null);
            $table->float('insheet_added')->nullable()->default(null);

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('parent_id')->references('id')->on('sales_product_processes');
            $table->foreign('unit_id')->references('id')->on('core_units');
            $table->foreign('product_id')->references('id')->on('sales_products');
            $table->foreign('dimension_unit_id')->references('id')->on('core_units');
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
