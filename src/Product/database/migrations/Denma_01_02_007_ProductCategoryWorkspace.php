<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ProductCategoryWorkspace extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('sales_product_category_workspaces', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('workspace_id')->unsigned();
            $table->integer('product_category_id')->unsigned();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('workspace_id')->references('id')->on('core_workspaces');
            $table->foreign('product_category_id')->references('id')->on('sales_product_categories');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('sales_product_category_workspaces');
    }
}
