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
        Schema::create('stock_reconciliations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('recId')->nullable()->index('recId');
            $table->date('rec_date')->nullable();
            $table->double('from')->nullable();
            $table->double('to')->nullable();
            $table->integer('product_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->bigInteger('branch_id')->nullable();
            $table->integer('business_id')->nullable();
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
        Schema::dropIfExists('stock_reconciliations');
    }
};
