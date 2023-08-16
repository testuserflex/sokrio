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
        Schema::create('sale_returns', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('sale_id')->nullable();
            $table->integer('product_id')->nullable();
            $table->double('quantity')->nullable();
            $table->double('unit_selling_price')->nullable()->comment('Selling price for each product');
            $table->double('amount_refunded')->nullable()->comment('total amount refund');
            $table->string('batch', 40)->nullable();
            $table->date('date')->nullable();
            $table->string('reason')->nullable();
            $table->integer('mode')->nullable();
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
        Schema::dropIfExists('sale_returns');
    }
};
