<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Bank extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('sales_banks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 50);
            $table->text('logo')->nullable()->default(null);
            $table->string('account_name', 50);
            $table->string('account_number', 50);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('sales_banks');
    }
}
