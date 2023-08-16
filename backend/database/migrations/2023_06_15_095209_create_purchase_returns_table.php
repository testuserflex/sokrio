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
        Schema::create('purchase_returns', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('purchase_id')->nullable();
            $table->integer('product_id')->nullable();
            $table->double('quantity')->nullable();
            $table->double('unit_buying_price')->nullable()->comment('buying price for each product');
            $table->double('amount_refunded')->nullable()->comment('total amount refund');
            $table->string('batch', 40)->nullable();
            $table->date('date')->nullable();
            $table->string('reason')->nullable();
            $table->integer('mode')->nullable();
            $table->boolean('type')->nullable()->default(false)->comment('0=shop, 1=store');
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
        Schema::dropIfExists('purchase_returns');
    }
};
