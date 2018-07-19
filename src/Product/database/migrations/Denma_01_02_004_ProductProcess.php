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
            $table->enum('type', ['good', 'service', 'manual']);
            $table->integer('reference_id')->nullable()->default(null);
            $table->string('reference_type')->nullable()->default(null);
            $table->integer('reference_default_id')->nullable()->default(null);
            $table->longText('reference_configurations')->nullable()->default(null);
            $table->string('name', 50);
            $table->string('specific', 50)->nullable()->default(null);
            $table->float('quantity');
            $table->bigInteger('unit_price')->default(0);
            $table->bigInteger('unit_total')->default(0);
            $table->integer('unit_id')->unsigned();

            // ratio
            $table->integer('ratio_order_quantity')->nullable()->default(1)->comment('0 is no matter how much');
            $table->integer('ratio_process_quantity')->default(1);

            // insheet
            $table->boolean('good_insheet')->default(false)->comment('except service');
            $table->float('good_insheet_multiples')->nullable()->default(null);
            $table->float('good_insheet_quantity')->nullable()->default(null);
            $table->float('good_insheet_default')->nullable()->default(null);

            $table->boolean('required')->default(true);
            $table->timestamps();
            $table->softDeletes();

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
