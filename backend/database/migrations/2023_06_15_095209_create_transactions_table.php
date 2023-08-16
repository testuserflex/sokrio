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
        Schema::create('transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('category')->nullable()->comment('sale=1, expense=2, purchase=3,sale_return=4,purchase_return=5,cashout=6,cashin=7,money transfer=8,customer deposit=9');
            $table->double('amount_in')->nullable()->default(0);
            $table->double('amount_out')->nullable()->default(0);
            $table->integer('mode')->nullable();
            $table->integer('tId')->nullable();
            $table->date('date')->nullable();
            $table->text('description')->nullable();
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
        Schema::dropIfExists('transactions');
    }
};
