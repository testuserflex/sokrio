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
        Schema::create('spoilt_stocks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('product_id')->nullable();
            $table->double('quantity')->nullable();
            $table->double('unit_buying_price')->nullable()->comment('Buying price for each product');
            $table->string('batch', 40)->nullable();
            $table->date('date_removed')->nullable();
            $table->string('reason')->nullable();
            $table->boolean('expired')->default(false)->comment('1=expired, 0=other reasons');
            $table->integer('confirm')->default(0);
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
        Schema::dropIfExists('spoilt_stocks');
    }
};
