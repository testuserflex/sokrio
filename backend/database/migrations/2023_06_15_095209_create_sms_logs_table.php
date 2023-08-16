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
        Schema::create('sms_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('receiver')->nullable();
            $table->integer('category')->nullable()->comment('1=daily_summery 2=product_customers 3=customers,debtors');
            $table->text('sms')->nullable();
            $table->integer('sms_id')->nullable();
            $table->integer('size')->nullable();
            $table->integer('branch_id')->nullable();
            $table->integer('business_id')->nullable();
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
        Schema::dropIfExists('sms_logs');
    }
};
