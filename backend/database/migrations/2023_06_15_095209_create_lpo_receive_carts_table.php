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
        Schema::create('lpo_receive_carts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('lpoId')->index();
            $table->double('qty')->nullable();
            $table->bigInteger('product_id')->nullable();
            $table->bigInteger('unit_price')->nullable();
            $table->bigInteger('batch')->nullable();
            $table->date('expiry')->nullable();
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
        Schema::dropIfExists('lpo_receive_carts');
    }
};
