<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Product1 extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('sales_products', function (Blueprint $table) {
            $table->dropColumn('base_price')->default(0);
            $table->dropColumn('per_unit_price')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('sales_products', function (Blueprint $table) {
            $table->bigInteger('base_price')->default(0)->after('order_multiples');
            $table->bigInteger('per_unit_price')->default(0)->after('base_price');
        });
    }
}
