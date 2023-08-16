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
        Schema::create('purchase_carts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('product_id')->nullable();
            $table->string('code', 100)->nullable();
            $table->double('quantity')->nullable();
            $table->bigInteger('unit')->nullable();
            $table->double('unit_price')->nullable();
            $table->double('unit_sellingprice')->nullable();
            $table->string('batch', 40)->nullable();
            $table->integer('supplier_id')->nullable();
            $table->string('expiry', 20)->nullable();
            $table->boolean('source')->default(false)->comment('0=direct_purchase 1=store');
            $table->integer('category')->default(0)->comment('0=shop 1=store');
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
        Schema::dropIfExists('purchase_carts');
    }
};
