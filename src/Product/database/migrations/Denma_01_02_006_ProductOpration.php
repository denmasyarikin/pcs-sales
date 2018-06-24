<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ProductOpration extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('sales_product_oprations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_process_id')->unsigned();
            $table->integer('product_configuration_id')->unsigned();
            $table->enum('condition', ['<', '<=', '=', '!=', '>', '>=', 'in', 'not_in']);
            $table->text('value');
            $table->longText('opration');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('product_process_id')->references('id')->on('sales_product_processes');
            $table->foreign('product_configuration_id')->references('id')->on('sales_product_configurations');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('sales_product_oprations');
    }
}
