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
        Schema::create('licence_notifications', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('days_remaining')->nullable();
            $table->double('annual_bill')->nullable();
            $table->string('contact')->nullable();
            $table->string('email')->nullable();
            $table->date('date')->nullable();
            $table->date('enddate')->nullable();
            $table->integer('business_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('licence_notifications');
    }
};
