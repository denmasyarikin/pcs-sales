<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class OrderItem extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('sales_order_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_id')->unsigned();
            $table->enum('type', ['product', 'good', 'service', 'manual']);
            $table->enum('type_as', ['product', 'good', 'service', 'manual']);
            $table->integer('reference_id')->nullable()->default(null);
            $table->string('reference_type')->nullable()->default(null);
            $table->integer('reference_second_id')->nullable()->default(null);
            $table->string('name', 50);
            $table->string('specific', 50)->nullable()->default(null);
            $table->float('quantity');
            $table->bigInteger('unit_price')->default(0);
            $table->bigInteger('unit_total')->default(0);
            $table->bigInteger('adjustment_total')->default(0);
            $table->bigInteger('total')->default(0);
            $table->integer('unit_id')->unsigned();
            $table->text('note')->nullable()->default(null);
            // dimension
            $table->boolean('depending_to_dimension')->default(false);
            $table->enum('dimension', ['length', 'area', 'volume', 'weight'])->nullable()->default(null);
            $table->integer('dimension_unit_id')->unsigned()->nullable()->default(null);
            $table->float('length')->nullable()->default(null);
            $table->float('width')->nullable()->default(null);
            $table->float('height')->nullable()->default(null);
            $table->float('weight')->nullable()->default(null);
            // increasment
            $table->enum('price_type', ['static', 'dynamic'])->nullable()->default(null);
            $table->float('price_increase_multiples')->nullable()->default(null);
            $table->float('price_increase_percentage')->nullable()->default(null);
            // insheet
            $table->boolean('insheet_required')->default(false);
            $table->enum('insheet_type', ['static', 'dynamic'])->nullable()->default(null);
            $table->float('insheet_multiples')->nullable()->default(null);
            $table->float('insheet_quantity')->nullable()->default(null);
            $table->float('insheet_added')->nullable()->default(null);

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('order_id')->references('id')->on('sales_orders');
            $table->foreign('unit_id')->references('id')->on('core_units');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('sales_order_items');
    }
}
