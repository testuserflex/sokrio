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
        Schema::create('sms_purchases', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('package_id')->nullable();
            $table->integer('sms')->nullable();
            $table->string('phone', 14)->nullable();
            $table->double('amount')->nullable();
            $table->integer('branch_id')->nullable();
            $table->integer('business_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->string('msg', 100)->nullable();
            $table->string('tid', 60)->nullable();
            $table->string('api_status', 20);
            $table->bigInteger('memo');
            $table->bigInteger('log_id');
            $table->tinyInteger('status')->default(1)->comment('1=successfull 0=unsuccessfull');
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
        Schema::dropIfExists('sms_purchases');
    }
};
