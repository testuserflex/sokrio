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
        Schema::create('product_units', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('product_id')->nullable();
            $table->string('product_code')->nullable();
            $table->integer('unit_id')->nullable();
            $table->double('base_quantity')->nullable();
            $table->double('selling_price')->nullable();
            $table->double('reserve_price')->nullable();
            $table->integer('wholesale_unitprice')->default(0);
            $table->integer('wholesale_reserveprice')->default(0);
            $table->tinyInteger('is_base')->default(0);
            $table->bigInteger('stock_movement')->default(0);
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
        Schema::dropIfExists('product_units');
    }
};
