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
        Schema::create('sale_carts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('product_id')->nullable();
            $table->string('code', 100)->nullable();
            $table->boolean('type')->default(true);
            $table->double('quantity')->nullable();
            $table->double('quantity_taken')->nullable();
            $table->double('selling_price')->nullable();
            $table->integer('unit')->nullable();
            $table->string('batch_id', 40)->nullable();
            $table->integer('hold')->default(0)->comment('0=notheld 1=held');
            $table->bigInteger('holdId')->nullable()->index('holdId');
            $table->integer('cart_status')->default(0);
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
        Schema::dropIfExists('sale_carts');
    }
};
