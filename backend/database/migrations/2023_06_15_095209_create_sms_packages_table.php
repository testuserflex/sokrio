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
        Schema::create('sms_packages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('package')->nullable();
            $table->integer('sms')->nullable();
            $table->double('total_cost')->nullable();
            $table->boolean('status')->nullable()->default(true);
            $table->integer('user_id')->nullable();
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
        Schema::dropIfExists('sms_packages');
    }
};
