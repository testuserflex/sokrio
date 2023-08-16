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
        Schema::create('consumable_stock_carts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('item_id')->nullable()->index();
            $table->bigInteger('unit_id')->nullable();
            $table->bigInteger('unit_price')->nullable();
            $table->bigInteger('quantity')->nullable()->index('consumable_stock_carts_unit_price_index');
            $table->bigInteger('user_id')->nullable()->index();
            $table->bigInteger('branch_id')->nullable()->index();
            $table->bigInteger('business_id')->nullable()->index();
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
        Schema::dropIfExists('consumable_stock_carts');
    }
};
