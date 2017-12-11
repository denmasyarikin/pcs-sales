<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Customer extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('sales_customers', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('type', ['public', 'agent', 'company'])->default('public');
            $table->string('name', 50);
            $table->text('address')->nullable()->default(null);
            $table->string('telephone', 20)->nullable()->default(null);
            $table->string('email', 50)->nullable()->default(null);
            $table->string('contact_person', 50)->nullable()->default(null);
            $table->integer('user_id')->unsigned()->nullable()->default(null);
            $table->timestamp('last_order')->nullable()->default(null);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('core_users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('sales_customers');
    }
}
