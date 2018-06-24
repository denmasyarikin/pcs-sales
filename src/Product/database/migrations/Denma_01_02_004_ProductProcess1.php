<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ProductProcess1 extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('sales_product_processes', function (Blueprint $table) {
            $table->dropColumn('service_configurable');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('sales_product_processes', function (Blueprint $table) {
            $table->boolean('service_configurable')->default(false)->after('good_insheet_default');
        });
    }
}