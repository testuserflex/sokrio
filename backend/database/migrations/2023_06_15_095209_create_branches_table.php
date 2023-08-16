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
        Schema::create('branches', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->nullable();
            $table->string('address')->nullable();
            $table->string('email')->nullable();
            $table->string('phone1', 14)->nullable();
            $table->string('phone2', 14)->nullable();
            $table->integer('country_id')->nullable();
            $table->integer('currency_code')->nullable();
            $table->bigInteger('notification_contact')->nullable();
            $table->integer('status')->default(1)->comment('0=inactive 1=active');
            $table->boolean('upload')->default(false)->comment('0=no 1=yes');
            $table->integer('user_id')->nullable();
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
        Schema::dropIfExists('branches');
    }
};
