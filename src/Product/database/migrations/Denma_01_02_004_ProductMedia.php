<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ProductMedia extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('sales_product_medias', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id')->unsigned();
            $table->enum('type', ['image', 'youtube'])->default('image');
            $table->text('content');
            $table->integer('sequence')->default(0);
            $table->boolean('primary')->default(false);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('product_id')->references('id')->on('sales_products');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('sales_product_medias');
    }
}
