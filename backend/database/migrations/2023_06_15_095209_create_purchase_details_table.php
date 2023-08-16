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
        Schema::create('purchase_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('product_id')->nullable();
            $table->bigInteger('unit')->nullable()->index('unit');
            $table->double('quantity')->nullable();
            $table->double('buying_price')->nullable();
            $table->double('selling_price')->nullable();
            $table->string('batch', 40)->nullable();
            $table->string('expiry', 20)->nullable();
            $table->integer('category')->default(0)->comment('0=shop 1=store');
            $table->boolean('return_status')->default(false)->comment('0=no 1=yes');
            $table->integer('purchase_id')->nullable();
            $table->integer('returned')->default(0);
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
        Schema::dropIfExists('purchase_details');
    }
};
