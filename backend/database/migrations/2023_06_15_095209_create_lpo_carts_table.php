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
        Schema::create('lpo_carts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('product_id')->index();
            $table->double('quantity')->nullable();
            $table->bigInteger('unit')->nullable()->index('unit');
            $table->integer('unit_price');
            $table->bigInteger('user_id')->index();
            $table->bigInteger('branch_id')->index();
            $table->bigInteger('business_id')->index();
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
        Schema::dropIfExists('lpo_carts');
    }
};
