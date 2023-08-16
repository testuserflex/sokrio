<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('country_name', 52)->nullable();
            $table->string('country_code', 17)->nullable();
            $table->string('dial_code', 17)->nullable();
            $table->string('currency_code', 32)->nullable();
            $table->string('currency', 29)->nullable();
            $table->string('capital_city', 19)->nullable();
            $table->string('country_dormain', 3)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('countries');
    }
};
