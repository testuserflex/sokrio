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
        Schema::create('lpo_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('lpo_id')->index();
            $table->bigInteger('product_id')->index();
            $table->double('quantity')->nullable();
            $table->double('received')->default(0);
            $table->bigInteger('unit')->nullable()->index('unit');
            $table->integer('unit_price');
            $table->boolean('status')->default(true)->comment('1=received all 0=still demanding');
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
        Schema::dropIfExists('lpo_details');
    }
};
