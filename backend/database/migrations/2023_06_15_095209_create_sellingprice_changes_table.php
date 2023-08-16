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
        Schema::create('sellingprice_changes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('product_id')->nullable();
            $table->integer('unit_id')->nullable();
            $table->bigInteger('old_price')->nullable();
            $table->bigInteger('new_price')->nullable();
            $table->bigInteger('branch_id')->nullable();
            $table->bigInteger('business_id')->nullable();
            $table->bigInteger('user_id')->index();
            $table->boolean('status')->default(false);
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
        Schema::dropIfExists('sellingprice_changes');
    }
};
