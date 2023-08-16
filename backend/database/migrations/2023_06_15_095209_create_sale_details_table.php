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
        Schema::create('sale_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('sale_id')->nullable()->index('sale_id');
            $table->integer('product_id')->nullable();
            $table->double('quantity')->nullable();
            $table->double('quantity_remaining')->nullable()->default(0);
            $table->double('buying_price')->nullable();
            $table->double('average_buyingprice')->nullable();
            $table->double('selling_price')->nullable();
            $table->integer('unit')->nullable();
            $table->string('batch_id', 40)->nullable();
            $table->integer('returned')->default(0);
            $table->integer('order_status')->default(0)->comment('0-pending, 1-prepared, 2-served');
            $table->string('description', 3000)->nullable();
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
        Schema::dropIfExists('sale_details');
    }
};
