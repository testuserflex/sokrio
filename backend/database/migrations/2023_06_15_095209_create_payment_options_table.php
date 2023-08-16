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
        Schema::create('payment_options', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->nullable()->comment('e.g Rancho');
            $table->string('type')->nullable()->comment('e.g Bank');
            $table->string('type_name')->nullable()->comment('e.g Centenary');
            $table->bigInteger('account_number')->nullable()->comment('e.g 0098789433982');
            $table->string('code', 100)->nullable();
            $table->double('balance')->nullable();
            $table->boolean('is_default')->default(false);
            $table->integer('status')->default(1)->comment('0=deleted 1=active');
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
        Schema::dropIfExists('payment_options');
    }
};
