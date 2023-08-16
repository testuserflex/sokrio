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
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('item_id')->nullable()->index();
            $table->integer('efriscategory')->nullable();
            $table->string('product_name')->nullable();
            $table->string('product_code')->nullable();
            $table->double('quantity')->nullable();
            $table->double('minimum_quantity')->nullable();
            $table->bigInteger('unit_id')->nullable()->index('unit_id');
            $table->double('selling_price')->nullable();
            $table->double('reserve_price')->nullable();
            $table->integer('wholesale_unitprice')->default(0);
            $table->integer('wholesale_reserveprice')->default(0);
            $table->tinyInteger('is_product')->default(0);
            $table->tinyInteger('vat')->default(0);
            $table->tinyInteger('status')->default(1)->comment('1=Active 0=deleted');
            $table->integer('branch_id')->nullable()->index();
            $table->integer('business_id')->nullable()->index();
            $table->integer('user_id')->nullable();
            $table->timestamps();
            $table->double('buy')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
};
