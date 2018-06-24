<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ProductOpration1 extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('sales_product_oprations', function (Blueprint $table) {
            $table->renameColumn('value', 'condition_value');
            $table->string('opration')->change();
            $table->longText('opration_value')->after('opration');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('sales_product_oprations', function (Blueprint $table) {
            $table->renameColumn('condition_value', 'value');
            $table->longText('opration')->change();
            $table->dropColumn('opration_value');
        });
    }
}